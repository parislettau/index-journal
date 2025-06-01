<?php

use Kirby\Cms\Response;

/**
 * Read Crossref-related site options.
 */
function crossrefOptions(): array
{
    $site = kirby()->site();
    return [
        // you can override apiUrl in the Panel; falls back to Crossref’s v2 endpoint
        'apiUrl'       => $site->crossref_apiUrl()->or('https://api.crossref.org/deposits')->value(),
        'username'     => $site->crossref_username()->value(),
        'password'     => $site->crossref_password()->value(),
        'journalTitle' => $site->crossref_journalTitle()->value(),
        'issn'         => $site->crossref_issn()->value(),
    ];
}

/**
 * Abort with 400 if any required option is missing.
 */
function validateCrossrefSettings(array $opts, ?array $keys = null): ?Response
{
    $labels = [
        'username'     => 'Crossref username',
        'password'     => 'Crossref password',
        'apiUrl'       => 'Crossref API URL',
        'journalTitle' => 'Journal title',
        'issn'         => 'Journal ISSN',
    ];

    $keys ??= array_keys($labels);

    foreach ($keys as $key) {
        if (empty($opts[$key])) {
            $label = $labels[$key] ?? $key;
            return new Response("Missing required Crossref setting: {$label}", 'text/plain', 400);
        }
    }
    return null;
}

/**
 * Kirby plugin.
 */
Kirby::plugin('custom/crossref-register', [
    'snippets' => [
        'confirm' => __DIR__ . '/snippets/confirm.php'
    ],
    'routes' => [
        [
            'pattern' => 'submit-crossref/(:all)',
            'method'  => 'GET|POST',
            'action'  => function (string $id) {

                // 1. basic guards ---------------------------------------------------
                if (!kirby()->user()) {
                    return new Response('Unauthorized', 'text/plain', 403);
                }

                if (!$issue = page($id)) {
                    return new Response('Issue not found', 'text/plain', 404);
                }

                // 2. gather metadata -----------------------------------------------
                [$issueData, $essaysData] = collectIssueData($issue);

                // 3. confirmation preview ------------------------------------------
                $request = kirby()->request();

                // var_dump($issueData, $essaysData);
                // exit;

                if ($request->method() === 'GET' && $request->get('confirm') !== '1') {
                    $html = snippet('confirm', [
                        'issue'      => $issue,
                        'issueData'  => $issueData,
                        'essaysData' => $essaysData,
                    ], true);                         // true → return HTML string
                    return new Response($html, 'text/html');
                }

                // 4. reject POSTs without confirm=1 --------------------------------
                if ($request->method() === 'POST') {
                    $confirm = $request->body()->get('confirm');
                    if ($confirm !== '1') {
                        return new Response('Confirmation required', 'text/plain', 400);
                    }
                }

                // 5. ready to talk to Crossref -------------------------------------
                $opts = crossrefOptions();
                if ($resp = validateCrossrefSettings($opts)) {
                    return $resp;                     // missing setting → abort
                }

                $xml       = generateXML($issueData, $essaysData);
                $apiResult = sendToCrossref($xml, $opts);

                return new Response($apiResult, 'application/json');
            },
        ],
    ],
]);

/**
 * Collect metadata for an issue and its descendant essays.
 */
function collectIssueData(Kirby\Cms\Page $issue): array
{
    $issueData = [
        'issue_title' => $issue->title()->value(),
        'doi'         => $issue->issue_doi()->value(),
        'issue_date'  => $issue->issue_date()->toDate('Y-m-d'),
        'issue_num'   => $issue->issue_num()->value(),
        'editors'     => $issue->editors()->toStructure()->toArray(),
        'url'         => $issue->url(),
        'year'        => $issue->issue_date()->toDate('Y'),
    ];

    $essays = kirby()->site()->index()
        ->filterBy('template', 'essay')
        ->filter(fn($c) => $c->isDescendantOf($issue));

    $essaysData = array_map(function ($essay) use ($issue) {
        return [
            'title'    => $essay->title()->value(),
            'subtitle' => $essay->subtitle()->value(),
            'authors'  => $essay->authors()->toStructure()->toArray(),
            'doi'      => $essay->doi()->value(),
            'abstract' => $essay->abstract()->value(),
            'url'      => $essay->url(),
            'year'     => $issue->issue_date()->toDate('Y'),
        ];
    }, $essays->values());

    return [$issueData, $essaysData];
}

function generateTimestamp(): string
{
    return date('YmdHis') . sprintf('%03d', (int)(microtime(true) * 1000) % 1000);
}


/**
 * Build Crossref 5.3.1 XML payload.
 */
function generateXML(array $issue, array $essays): string
{
    $site = kirby()->site();
    $journalTitle = $site->crossref_journalTitle()->value();
    $issn         = $site->crossref_issn()->value();

    $esc = fn($s) => htmlspecialchars($s ?? '', ENT_XML1 | ENT_COMPAT, 'UTF-8');

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'
        . '<doi_batch version="5.3.1" '
        . 'xmlns="http://www.crossref.org/schema/5.3.1" '
        . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
        . 'xsi:schemaLocation="http://www.crossref.org/schema/5.3.1 '
        . 'http://www.crossref.org/schemas/crossref5.3.1.xsd">'

        // head
        . '<head>'
        . '<doi_batch_id>' . generateBatchId() . '</doi_batch_id>'
        . '<timestamp>' . generateTimestamp() . '</timestamp>'
        . '<depositor><depositor_name>indj</depositor_name>'
        . '<email_address>editors@index-journal.org</email_address></depositor>'
        . '<registrant>WEB-FORM</registrant>'
        . '</head>'

        // body / journal
        . '<body><journal>'

        . '<journal_metadata>'
        . '<full_title>' . $esc($journalTitle) . '</full_title>'
        . '<issn media_type="electronic">' . $esc($issn) . '</issn>'
        . '</journal_metadata>'

        . '<journal_issue>'
        . '<contributors>';

    $buildEditor = function (array $editor, string $sequence) use ($esc) {
        $given   = $editor['first_name'] ?? null;
        $surname = $editor['last_name']  ?? null;

        if (!$given && !$surname && isset($editor['name'])) {
            $parts   = preg_split('/\s+/', $editor['name'], 2);
            $given   = $parts[0] ?? '';
            $surname = $parts[1] ?? '';
        }

        $xml = '<person_name contributor_role="editor" sequence="' . $sequence . '">' .
            '<given_name>' . $esc($given) . '</given_name>' .
            '<surname>'    . $esc($surname) . '</surname>';

        if (!empty($editor['orcid'])) {
            $xml .= '<ORCID>' . $esc($editor['orcid']) . '</ORCID>';
        }

        return $xml . '</person_name>';
    };

    foreach ($issue['editors'] as $i => $editor) {
        $sequence = $i === 0 ? 'first' : 'additional';
        $xml .= $buildEditor($editor, $sequence);
    }

    $xml .= '</contributors>'
        . '<publication_date media_type="online"><year>' . $esc($issue['year']) . '</year></publication_date>'
        . '<issue>' . $esc($issue['issue_num']) . '</issue>'
        . '<doi_data><doi>' . $esc($issue['doi']) . '</doi><resource>' . $esc($issue['url']) . '</resource></doi_data>'
        . '</journal_issue>';

    foreach ($essays as $essay) {

        // support either separate first/last or a single name field
        $buildPerson = function (array $author, string $sequence) use ($esc) {
            $given   = $author['first_name'] ?? null;
            $surname = $author['last_name']  ?? null;

            if (!$given && !$surname && isset($author['name'])) {
                $parts   = preg_split('/\s+/', $author['name'], 2);
                $given   = $parts[0] ?? '';
                $surname = $parts[1] ?? '';
            }

            return '<person_name contributor_role="author" sequence="' . $sequence . '">'
                . '<given_name>' . $esc($given) . '</given_name>'
                . '<surname>'    . $esc($surname) . '</surname>'
                . '</person_name>';
        };

        $xml .= '<journal_article publication_type="full_text">'
            . '<titles><title>' . $esc($essay['title']) . '</title></titles>'
            . '<contributors>';

        foreach ($essay['authors'] as $i => $author) {
            $sequence = $i === 0 ? 'first' : 'additional';
            $xml .= $buildPerson($author, $sequence);
        }

        $xml .= '</contributors>'
            . '<publication_date media_type="online"><year>' . $esc($essay['year']) . '</year></publication_date>'
            . '<doi_data><doi>' . $esc($essay['doi']) . '</doi><resource>' . $esc($essay['url']) . '</resource></doi_data>'
            . '</journal_article>';
    }

    $xml .= '</journal></body></doi_batch>';

    return $xml;
}

/**
 * Simple batch id.
 */
function generateBatchId(): string
{
    return 'batch_' . date('YmdHis');
}

/**
 * Upload XML to Crossref and return a JSON string describing the outcome.
 */
function sendToCrossref(string $xml, ?array $opt = null): string
{
    $opt ??= crossrefOptions();

    $url  = $opt['apiUrl']   ?? 'https://api.crossref.org/deposits';
    $user = $opt['username'] ?? '';
    $pass = $opt['password'] ?? '';

    // 1. temp file and multipart/form-data POST
    $tmp = tempnam(sys_get_temp_dir(), 'cr_');
    file_put_contents($tmp, $xml);
    $curlFile = curl_file_create($tmp, 'application/xml', 'metadata.xml');

    // choose field names depending on endpoint flavour
    if (str_contains($url, '/servlet/deposit')) {       // legacy
        $post = [
            'operation'    => 'doMDUpload',
            'login_id'     => $user,
            'login_passwd' => $pass,
            'fname'        => $curlFile,
        ];
    } else {                                            // v2 sync
        $post = [
            'operation' => 'doMDUpload',
            'usr'       => $user,
            'pwd'       => $pass,
            'mdFile'    => $curlFile,
        ];
    }

    // 2. cURL
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_HEADER         => true,
    ]);
    $raw     = curl_exec($ch);
    $curlErr = curl_error($ch);
    $info    = curl_getinfo($ch);
    curl_close($ch);
    @unlink($tmp);

    // 3. split headers/body
    $headerSize = $info['header_size'] ?? 0;
    $body       = substr($raw, $headerSize);

    // 4. build response object
    $out = [
        'http_code'     => $info['http_code'] ?? 0,
        'curl_error'    => $curlErr ?: null,
        'submission_id' => null,
        'batch_id'      => null,
        'records'       => [],
        'summary'       => [],
    ];

    // 4a. parse XML
    if (str_starts_with(trim($body), '<?xml')) {
        try {
            $xmlObj = new SimpleXMLElement($body);

            if ($xmlObj->getName() === 'doi_batch_diagnostic') {
                $out['submission_id'] = (string) $xmlObj->submission_id ?? null;
                $out['batch_id']      = (string) $xmlObj->batch_id ?? null;

                $success = $warning = $failure = 0;

                foreach ($xmlObj->record_diagnostic as $diag) {
                    $status = (string) $diag['status'];
                    $doi    = (string) $diag->doi ?? '';
                    $msg    = (string) $diag->msg ?? '';

                    $conflict = [];
                    if ($status === 'Warning' && isset($diag->dois_in_conflict)) {
                        foreach ($diag->dois_in_conflict->doi as $c) {
                            $conflict[] = (string) $c;
                        }
                    }

                    $out['records'][] = [
                        'doi'      => $doi,
                        'status'   => $status,
                        'message'  => $msg,
                        'conflict' => $conflict,
                    ];

                    if ($status === 'Success')     $success++;
                    elseif ($status === 'Warning') $warning++;
                    elseif ($status === 'Failure') $failure++;
                }

                $out['summary'] = [
                    'success' => $success,
                    'warning' => $warning,
                    'failure' => $failure,
                ];
            } else {
                $out['raw_xml'] = $body;
            }
        } catch (Throwable $e) {
            $out['parse_error'] = 'Invalid response XML';
            $out['raw'] = $body;
        }
    } elseif (str_starts_with(trim($body), '{')) {
        $out['json'] = json_decode($body, true);
    } else {
        $out['body'] = $body ?: null;
    }

    return json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
