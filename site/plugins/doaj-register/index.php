<?php

use Kirby\Cms\Response;

/**
 * Read DOAJ-related config options.
 */
function doajOptions(): array
{
    $site = kirby()->site();
    return [
        'apiUrl'      => $site->doaj_apiUrl()->or('https://doaj.org/api/articles')->value(),
        'bulkApiUrl'  => $site->doaj_bulkApiUrl()->or('https://doaj.org/api/v4/bulk/articles')->value(),
        'apiKey'      => $site->doaj_apiKey()->value(),
    ];
}

/**
 * Abort with 400 if required options are missing.
 */
function validateDoajSettings(array $opts): ?Response
{
    if (empty($opts['apiKey'])) {
        return new Response('Missing DOAJ API key', 'text/plain', 400);
    }
    return null;
}

/**
 * Kirby plugin.
 */
Kirby::plugin('custom/doaj-register', [
    'snippets' => [
        'doaj-confirm' => __DIR__ . '/snippets/confirm.php',
        'doaj-confirm-bulk' => __DIR__ . '/snippets/confirm-bulk.php'
    ],
    'routes' => [
        [
            'pattern' => 'submit-doaj/(:all)',
            'method'  => 'GET|POST',
            'action'  => function (string $id) {

                // 1. guards --------------------------------------------------
                if (!kirby()->user()) {
                    return new Response('Unauthorized', 'text/plain', 403);
                }

                if (!$essay = page($id)) {
                    return new Response('Page not found', 'text/plain', 404);
                }

                if ($essay->template() != 'essay') {
                    return new Response('Invalid page type (' . $essay->template() . ')', 'text/plain', 400);
                }

                // 2. gather metadata ---------------------------------------
                $data = collectDoajData($essay);

                // 3. confirmation preview ---------------------------------
                $request = kirby()->request();

                if ($request->method() === 'GET' && $request->get('confirm') !== '1') {
                    $html = snippet('doaj-confirm', [
                        'essay' => $essay,
                        'data'  => $data,
                    ], true);
                    return new Response($html, 'text/html');
                }

                // 4. reject POSTs without confirm=1 ------------------------
                if ($request->method() === 'POST') {
                    $confirm = $request->body()->get('confirm');
                    if ($confirm !== '1') {
                        return new Response('Confirmation required', 'text/plain', 400);
                    }
                }

                // 5. send to DOAJ ------------------------------------------
                $opts = doajOptions();
                if ($resp = validateDoajSettings($opts)) {
                    return $resp; // missing setting → abort
                }

                $result = sendToDoaj($data, $opts);

                $decoded = json_decode($result, true);
                if (($decoded['http_code'] ?? 0) === 201 && !empty($decoded['body'])) {
                    $bodyJson = json_decode($decoded['body'], true);
                    if (!empty($bodyJson['id'])) {
                        try {
                            $essay->update(['doaj_id' => $bodyJson['id']]);
                        } catch (Throwable $e) {
                            // ignore update errors
                        }
                    }
                }

                return new Response($result, 'application/json');
            },
        ],
        [
            'pattern' => 'submit-doaj-bulk/(:all)',
            'method'  => 'GET|POST',
            'action'  => function (string $id) {

                // 1. guards --------------------------------------------------
                if (!kirby()->user()) {
                    return new Response('Unauthorized', 'text/plain', 403);
                }

                if (!$issue = page($id)) {
                    return new Response('Issue not found', 'text/plain', 404);
                }

                if ($issue->template() != 'issue') {
                    return new Response('Invalid page type (' . $issue->template() . ')', 'text/plain', 400);
                }

                // 2. gather metadata ---------------------------------------
                $essays = kirby()->site()->index()
                    ->filterBy('template', 'essay')
                    ->filter(fn($c) => $c->isDescendantOf($issue));

                $articles = array_map(fn($e) => collectDoajData($e), $essays->values());

                // 3. confirmation preview ---------------------------------
                $request = kirby()->request();

                if ($request->method() === 'GET' && $request->get('confirm') !== '1') {
                    $html = snippet('doaj-confirm-bulk', [
                        'issue'    => $issue,
                        'articles' => $articles,
                    ], true);
                    return new Response($html, 'text/html');
                }

                // 4. reject POSTs without confirm=1 ------------------------
                if ($request->method() === 'POST') {
                    $confirm = $request->body()->get('confirm');
                    if ($confirm !== '1') {
                        return new Response('Confirmation required', 'text/plain', 400);
                    }
                }

                // 5. send to DOAJ ------------------------------------------
                $opts = doajOptions();
                if ($resp = validateDoajSettings($opts)) {
                    return $resp; // missing setting → abort
                }

                $result = sendBulkToDoaj($articles, $opts);

                return new Response($result, 'application/json');
            },
        ],
    ],
]);

/**
 * Collect metadata for an essay page.
 */
function collectDoajData(Kirby\Cms\Page $essay): array
{
    $site  = kirby()->site();
    $issue = $essay->parent();

    $authors = [];
    foreach ($essay->authors()->toStructure()->toArray() as $a) {
        $name = trim(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));
        if (!$name && isset($a['name'])) {
            $name = $a['name'];
        }
        $authors[] = ['name' => $name];
    }

    $identifiers = [];
    $issn = $site->crossref_issn()->value();
    if ($issn !== '') {
        $identifiers[] = ['type' => 'eissn', 'id' => $issn];
    }
    if ($essay->doi()->isNotEmpty()) {
        $identifiers[] = ['type' => 'doi', 'id' => $essay->doi()->value()];
    }

    return [
        'bibjson' => [
            'title'  => $essay->title()->value(),
            'journal' => [
                'title' => $site->title()->value(),
                'issn'  => $site->crossref_issn()->value(),
            ],
            'year'  => $issue->issue_date()->toDate('Y'),
            'month' => $issue->issue_date()->toDate('m'),
            'link'  => [
                ['type' => 'fulltext', 'url' => $essay->url()],
            ],
            'identifier' => $identifiers,
            'author' => $authors,
            'abstract' => $essay->abstract()->value(),
        ],
    ];
}

/**
 * Upload JSON to DOAJ and return a JSON string describing the outcome.
 */
function sendToDoaj(array $data, ?array $opt = null): string
{
    $opt ??= doajOptions();

    $url    = $opt['apiUrl'] ?? 'https://doaj.org/api/articles';
    $apiKey = $opt['apiKey'] ?? '';

    $querySep = str_contains($url, '?') ? '&' : '?';
    $url .= $querySep . 'api_key=' . rawurlencode($apiKey);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
        ],
        CURLOPT_HEADER         => true,
    ]);
    $raw  = curl_exec($ch);
    $err  = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    $headerSize = $info['header_size'] ?? 0;
    $body       = substr($raw, $headerSize);

    return json_encode([
        'http_code'  => $info['http_code'] ?? 0,
        'curl_error' => $err ?: null,
        'body'       => $body ?: null,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

/**
 * Upload multiple articles to DOAJ and return a JSON string describing the outcome.
 */
function sendBulkToDoaj(array $articles, ?array $opt = null): string
{
    $opt ??= doajOptions();

    // derive bulk endpoint from single apiUrl if no explicit option
    $url = $opt['bulkApiUrl'] ?? null;
    if (!$url) {
        $base = $opt['apiUrl'] ?? 'https://doaj.org/api/articles';
        $url = preg_replace('#/articles$#', '/bulk/articles', $base);
        if ($url === null) {
            $url = 'https://doaj.org/api/v4/bulk/articles';
        }
    }
    $apiKey = $opt['apiKey'] ?? '';

    $querySep = str_contains($url, '?') ? '&' : '?';
    $url .= $querySep . 'api_key=' . rawurlencode($apiKey);

    $payload = ['articles' => $articles];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
        ],
        CURLOPT_HEADER         => true,
    ]);
    $raw  = curl_exec($ch);
    $err  = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    $headerSize = $info['header_size'] ?? 0;
    $body       = substr($raw, $headerSize);

    return json_encode([
        'http_code'  => $info['http_code'] ?? 0,
        'curl_error' => $err ?: null,
        'body'       => $body ?: null,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
