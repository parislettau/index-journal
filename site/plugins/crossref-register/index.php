<?php

use Kirby\Cms\Response;

Kirby::plugin('custom/crossref', [
    'routes' => [
        [
            'pattern' => 'generate-xml/(:all)',
            'action'  => function ($id) {
                $issue = page($id);
                if (!$issue) {
                    return new Response('Issue not found', 'text/plain', 404);
                }

                [$issueData, $essaysData] = collectIssueData($issue);
                $xml = generateXML($issueData, $essaysData);

                $filename = 'issue-' . $issueData['issue_num'] . '-' . generateBatchId() . '.xml';
                return new Response($xml, 'application/xml', 200, [
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"'
                ]);
            }
        ],
        [
            'pattern' => 'submit-crossref/(:all)',
            'action'  => function ($id) {
                if (!kirby()->user()) {
                    return new Response('Unauthorized', 'text/plain', 403);
                }

                $issue = page($id);
                if (!$issue) {
                    return new Response('Issue not found', 'text/plain', 404);
                }

                [$issueData, $essaysData] = collectIssueData($issue);
                $xml       = generateXML($issueData, $essaysData);
                $apiResult = sendToCrossref($xml);

                return new Response($apiResult, 'application/json');
            }
        ]
    ]
]);

/**
 * Collect metadata for an issue and its descendant essays.
 */
function collectIssueData($issue): array
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

    $essays = kirby()->site()->index()->filterBy('template', 'essay')->filter(fn($c) => $c->isDescendantOf($issue));
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

/**
 * Build Crossref 5.3.1 XML (minimal but valid).
 */
function generateXML(array $issue, array $essays): string
{
    $opts   = kirby()->option('crossref', []);
    $issn   = $opts['issn']   ?? '0000-0000';
    $journalTitle = $opts['journalTitle'] ?? 'Example Journal';

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
        . '<timestamp>' . date('YmdHis') . '</timestamp>'
        . '<depositor><depositor_name>indj</depositor_name><email_address>editors@index-journal.org</email_address></depositor>'
        . '<registrant>WEB-FORM</registrant>'
        . '</head>'

        // body / journal
        . '<body><journal>'

        . '<journal_metadata>'
        . '<full_title>' . $esc($journalTitle) . '</full_title>'
        . '<issn media_type="electronic">' . $esc($issn) . '</issn>'
        . '</journal_metadata>'

        . '<journal_issue>'
        . '<publication_date media_type="online"><year>' . $esc($issue['year']) . '</year></publication_date>'
        . '<issue>' . $esc($issue['issue_num']) . '</issue>'
        . '<doi_data><doi>' . $esc($issue['doi']) . '</doi><resource>' . $esc($issue['url']) . '</resource></doi_data>'
        . '</journal_issue>';

    foreach ($essays as $essay) {
        $xml .= '<journal_article publication_type="full_text">'
            . '<titles><title>' . $esc($essay['title']) . '</title></titles>'
            . '<contributors>';
        foreach ($essay['authors'] as $i => $author) {
            $sequence = $i === 0 ? 'first' : 'additional';
            $xml .= '<person_name contributor_role="author" sequence="' . $sequence . '">'
                . '<given_name>' . $esc($author['first_name'] ?? '') . '</given_name>'
                . '<surname>' . $esc($author['last_name'] ?? '') . '</surname>'
                . '</person_name>';
        }
        $xml .= '</contributors>'
            . '<publication_date media_type="online"><year>' . $esc($essay['year']) . '</year></publication_date>'
            . '<doi_data><doi>' . $esc($essay['doi']) . '</doi><resource>' . $esc($essay['url']) . '</resource></doi_data>'
            . '</journal_article>';
    }

    $xml .= '</journal></body></doi_batch>';

    return $xml;
}

function generateBatchId(): string
{
    return 'batch_' . date('YmdHis');
}

/**
 * POST the XML to Crossref using the correct multipart/form-data payload.
 */
/**
 * POST the XML to Crossref and return a detailed result object.
 */
function sendToCrossref(string $xml): string
{
    $opt   = kirby()->option('crossref', []);
    $url   = $opt['apiUrl']   ?? 'https://doi.crossref.org/servlet/deposit';
    $user  = $opt['username'] ?? '';
    $pass  = $opt['password'] ?? '';

    // 1. temp file and multipart/form-data POST
    $tmp = tempnam(sys_get_temp_dir(), 'cr_');
    file_put_contents($tmp, $xml);
    $curlFile = curl_file_create($tmp, 'application/xml', 'metadata.xml');

    // choose field names depending on endpoint flavour
    if (str_contains($url, '/servlet/deposit')) {
        $post = [
            'operation'    => 'doMDUpload',
            'login_id'     => $user,
            'login_passwd' => $pass,
            'fname'        => $curlFile,
        ];
    } else { // synchronous /v2/deposits
        $post = [
            'operation' => 'doMDUpload',
            'usr'       => $user,
            'pwd'       => $pass,
            'mdFile'    => $curlFile,
        ];
    }

    // 2. cURL with headers captured
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $post,
        CURLOPT_HEADER         => true,            // capture headers
    ]);
    $raw      = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $info     = curl_getinfo($ch);
    curl_close($ch);
    @unlink($tmp);

    // 3. split headers/body
    $headerSize = $info['header_size'] ?? 0;
    $body       = substr($raw, $headerSize);

    // 4. Build response object
    $out = [
        'http_code' => $info['http_code'] ?? 0,
        'curl_error' => $curlErr ?: null,
        'body' => $body ?: null,
    ];

    // 4a. Try to parse Crossref’s normal diagnostic XML
    if (str_starts_with(trim($body), '<?xml')) {
        try {
            $xmlObj = new SimpleXMLElement($body);
            // <doi_batch_diagnostic> is the root for both servlet and v2
            if ($xmlObj->getName() === 'doi_batch_diagnostic') {
                $out['status']        = (string) $xmlObj['status'];
                $out['submission_id'] = (string) $xmlObj->submission_id ?? null;
                $out['batch_id']      = (string) $xmlObj->batch_id      ?? null;

                // extract first record-level diagnostic if present
                if (isset($xmlObj->record_diagnostic)) {
                    $diag = $xmlObj->record_diagnostic;
                    $out['record_status'] = (string) $diag['status'];
                    $out['message']       = trim((string) $diag);
                    $out['msg_id']        = (string) $diag['msg_id'] ?? null;
                }
            } else {
                // some other XML – just send raw
                $out['xml'] = $body;
            }
        } catch (Throwable $e) {
            // malformed XML – return raw for inspection
            $out['xml'] = $body;
        }
    } elseif (str_starts_with(trim($body), '{')) {
        // v2 endpoint sometimes responds in JSON
        $out['json'] = json_decode($body, true);
    } else {
        // plain text or empty
        $out['body'] = $body ?: null;
    }

    return json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
