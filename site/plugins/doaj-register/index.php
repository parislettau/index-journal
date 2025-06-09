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
                $data     = collectDoajData($essay);
                $existing = null;
                if ($essay->doaj_id()->isNotEmpty()) {
                    $existing = fetchDoajRecord($essay->doaj_id()->value());
                }

                // 3. confirmation preview ---------------------------------
                $request = kirby()->request();

                if ($request->method() === 'GET' && $request->get('confirm') !== '1') {
                    $html = snippet('doaj-confirm', [
                        'essay'    => $essay,
                        'data'     => $data,
                        'existing' => $existing,
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
                        $recordUrl = 'https://doaj.org/api/v4/articles/' . $bodyJson['id'];
                        try {
                            $essay->update([
                                'doaj_id'         => $bodyJson['id'],
                                'doaj_record_url' => $recordUrl,
                            ]);
                        } catch (Throwable $e) {
                            // ignore update errors
                        }
                    }
                }

                if (($decoded['http_code'] ?? 0) === 201 && !empty($bodyJson['id']) && !empty($bodyJson['location'])) {
                    $articleUrl = 'https://doaj.org' . $bodyJson['location'];
                    $html = '<h2>Article Submitted to DOAJ</h2>';
                    $html .= '<p>Your article was successfully registered.</p>';
                    $html .= '<ul>';
                    $html .= '<li><strong>DOAJ ID:</strong> ' . htmlspecialchars($bodyJson['id']) . '</li>';
                    $html .= '<li><strong>DOAJ Record:</strong> <a href="' . htmlspecialchars($articleUrl) . '" target="_blank">' . htmlspecialchars($articleUrl) . '</a></li>';
                    $html .= '</ul>';
                    return new Response($html, 'text/html');
                } else {
                    return new Response('<pre>' . htmlspecialchars($result) . '</pre>', 'text/html');
                }
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

                $articles = array_map(function ($e) {
                    return [
                        'data'     => collectDoajData($e),
                        'existing' => $e->doaj_id()->isNotEmpty()
                            ? fetchDoajRecord($e->doaj_id()->value())
                            : null,
                    ];
                }, $essays->values());

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

                $payload = array_column($articles, 'data');
                $result  = sendBulkToDoaj($payload, $opts);

                $decoded = json_decode($result, true);
                if (($decoded['http_code'] ?? 0) === 202 && !empty($decoded['body'])) {
                    $bodyJson = json_decode($decoded['body'], true);
                    $data = [];
                    if (!empty($bodyJson['upload_id'])) {
                        $data['doaj_upload_id'] = $bodyJson['upload_id'];
                    }
                    if (!empty($bodyJson['status'])) {
                        $data['doaj_status'] = $bodyJson['status'];
                    }
                    if ($data) {
                        try {
                            $issue->update($data);
                        } catch (Throwable $e) {
                            // ignore update errors
                        }
                    }
                }

                $body    = json_decode($decoded['body'] ?? '', true);

                if (isset($body['upload_id'], $body['status'])) {
                    $html = '<h2>Bulk Submission Accepted</h2>';
                    $html .= '<p>Your article batch is now being processed by DOAJ.</p>';
                    $html .= '<ul>';
                    $html .= '<li><strong>Upload ID:</strong> ' . htmlspecialchars($body['upload_id']) . '</li>';
                    $html .= '<li><strong>Status URL:</strong> <a href="' . htmlspecialchars($body['status']) . '" target="_blank">' . htmlspecialchars($body['status']) . '</a></li>';
                    $html .= '</ul>';
                    $html .= '<p>You can check this link in a few minutes to track the progress of your submission.</p>';
                    // $html .= '<pre>' . $body . '</pre>';
                    return new Response($html, 'text/html');
                } else {
                    return new Response('<pre>' . htmlspecialchars($result) . '</pre>', 'text/html');
                }
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

    $structured = $essay->authors()->toStructure();
    if ($structured->isNotEmpty()) {
        foreach ($structured as $a) {
            $first = trim($a->first_name()->value());
            $last  = trim($a->last_name()->value());
            $name  = trim("$first $last");

            // If name is blank, fallback to flat 'name' field if available
            if ($name === '' && $a->name()->isNotEmpty()) {
                $name = $a->name()->value();
            }

            $authorEntry = ['name' => $name];

            if ($a->orcid()->isNotEmpty()) {
                $authorEntry['orcid_id'] = $a->orcid()->value();
            }

            $authors[] = $authorEntry;
        }
    }

    // Fallback: use `author` text field if structure is empty
    if (empty($authors) && $essay->author()->isNotEmpty()) {
        $authors[] = ['name' => $essay->author()->value()];
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
            'title' => $essay->subtitle()->isNotEmpty()
                ? $essay->title()->value() . ': ' . $essay->subtitle()->value()
                : $essay->title()->value(),
            'journal' => [
                'title' => $site->title()->value(),
                'issn'  => $site->crossref_issn()->value(),
            ],
            'year'  => $issue->issue_date()->toDate('Y'),
            'month' => $issue->issue_date()->toDate('m'),
            'link'  => [
                ['type' => 'fulltext', 'url' => 'https://index-journal.org/' . $essay->id()],
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

    $payload = $articles;

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

/**
 * Fetch an existing DOAJ record.
 */
function fetchDoajRecord(string $id): ?array
{
    $url = 'https://doaj.org/api/v4/articles/' . rawurlencode($id);
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FAILONERROR    => false,
    ]);
    $body = curl_exec($ch);
    curl_close($ch);

    if ($body === false || $body === null) {
        return null;
    }

    return json_decode($body, true);
}
