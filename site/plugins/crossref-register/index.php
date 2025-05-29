
<?php

use Kirby\Toolkit\Collection;
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

                $issueData = [
                    'issue_title' => $issue->title()->value(),
                    'doi'         => $issue->issue_doi()->value(),
                    'issue_date'  => $issue->issue_date()->toDate('Y-m-d'),
                    'issue_num'   => $issue->issue_num()->value(),
                    'editors'     => $issue->editors()->toStructure()->toArray(),
                    'url'         => $issue->url(),
                    'year'        => $issue->issue_date()->toDate('Y'),

                    // ... Add more fields as needed
                ];

                // Fetch descendants that are essays of the issue page
                $essays = kirby()->site()->index()->filterBy('template', 'essay')->filter(function ($child) use ($issue) {
                    return $child->isDescendantOf($issue);
                });

                $essaysData = [];
                foreach ($essays as $essay) {
                    $essaysData[] = [
                        'title'    => $essay->title()->value(),
                        'subtitle' => $essay->subtitle()->value(),
                        'authors'  => $essay->authors()->toStructure()->toArray(),
                        'doi'      => $essay->doi()->value(),
                        'abstract' => $essay->abstract()->value(),
                        'url'      => $essay->url(),
                        'year'     => $issue->issue_date()->toDate('Y'),
                    ];
                }

                // Generate XML
                $xml = generateXML($issueData, $essaysData);

                // Return XML as downloadable response
                return new Response($xml, 'application/xml', 200, [
                    'Content-Disposition' => 'attachment; filename="' . 'Issue No. ' . $issueData['issue_num'] . ', ' .  $issueData['issue_title'] . ' - ' . generateBatchId() . '.xml"'
                ]);
            }
        ],
        [
            'pattern' => 'submit-crossref/(:all)',
            'action'  => function ($id) {
                $user = kirby()->user();
                if (!$user) {
                    return new Response('Unauthorized', 'text/plain', 403);
                }

                $issue = page($id);

                if (!$issue) {
                    return new Response('Issue not found', 'text/plain', 404);
                }

                $issueData = [
                    'issue_title' => $issue->title()->value(),
                    'doi'         => $issue->issue_doi()->value(),
                    'issue_date'  => $issue->issue_date()->toDate('Y-m-d'),
                    'issue_num'   => $issue->issue_num()->value(),
                    'editors'     => $issue->editors()->toStructure()->toArray(),
                    'url'         => $issue->url(),
                    'year'        => $issue->issue_date()->toDate('Y'),
                ];

                $essays = kirby()->site()->index()->filterBy('template', 'essay')->filter(function ($child) use ($issue) {
                    return $child->isDescendantOf($issue);
                });

                $essaysData = [];
                foreach ($essays as $essay) {
                    $essaysData[] = [
                        'title'    => $essay->title()->value(),
                        'subtitle' => $essay->subtitle()->value(),
                        'authors'  => $essay->authors()->toStructure()->toArray(),
                        'doi'      => $essay->doi()->value(),
                        'abstract' => $essay->abstract()->value(),
                        'url'      => $essay->url(),
                        'year'     => $issue->issue_date()->toDate('Y'),
                    ];
                }

                $xml = generateXML($issueData, $essaysData);

                $response = sendToCrossref($xml);

                return new Response($response, 'application/json');
            }
        ]
    ]
]);

function generateXML($issueData, $essaysData)
{
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<doi_batch version="5.3.1" xmlns="http://www.crossref.org/schema/5.3.1">';  // Update to the newer schema version

    // Head section
    $xml .= '<head>';
    $xml .= '<doi_batch_id>' . generateBatchId() . '</doi_batch_id>';  // Assuming a function to generate a unique batch ID
    $xml .= '<timestamp>' . time() . '</timestamp>';
    $xml .= '<depositor><depositor_name>indj</depositor_name><email_address>editors@index-journal.org</email_address></depositor>';
    $xml .= '<registrant>WEB-FORM</registrant>';
    $xml .= '</head>';

    // Body
    $xml .= '<body>';
    // Journal section
    $xml .= '<journal>';
    $xml .= '<journal_metadata>';
    $xml .= '<full_title>Index Journal</full_title>';
    $xml .= '<abbrev_title>Index</abbrev_title>';
    $xml .= '<doi_data>';
    $xml .= '<doi>10.38030/index-journal</doi>';
    $xml .= '<resource>https://index-journal.org</resource>';
    $xml .= '</doi_data>';
    // ... More static values here
    $xml .= '</journal_metadata>';

    // ... Populate issue details using $issueData
    $xml .= '<journal_issue>';
    $xml .= '<publication_date media_type="online"><year>' . $issueData['year'] . '</year></publication_date>';
    $xml .= '<issue>' . $issueData['issue_num'] . '</issue>';
    $xml .= '<doi_data>';
    $xml .= '<doi>' . $issueData['doi'] . '</doi>';
    $xml .= '<resource>' . $issueData['url'] . '</resource>';  // Replace with the appropriate URL pattern
    $xml .= '</doi_data>';
    $xml .= '</journal_issue>';

    // Journal articles (essays) section
    foreach ($essaysData as $essay) {
        $xml .= '<journal_article>';
        $xml .= '<titles><title>' . $essay['title'] . '</title></titles>';
        $xml .= '<contributors>';
        foreach ($essay['authors'] as $author) {
            $xml .= '<person_name contributor_role="author" sequence="first">';
            $xml .= '<given_name>' . $author['first_name'] . '</given_name>';
            $xml .= '<surname>' . $author['last_name'] . '</surname>';
            $xml .= '</person_name>';
        }
        $xml .= '</contributors>';
        $xml .= '<publication_date media_type="online"><year>' . $essay['year'] . '</year></publication_date>';  // Replace with the appropriate year
        $xml .= '<doi_data>';
        $xml .= '<doi>' . $essay['doi'] . '</doi>';
        $xml .= '<resource>' . $essay['url'] . '</resource>';  // Replace with the appropriate URL pattern for essays
        $xml .= '</doi_data>';
        $xml .= '</journal_article>';
    }

    $xml .= '</journal>';
    $xml .= '</body>';

    $xml .= '</doi_batch>';

    return $xml;
}

function generateBatchId()
{
    // Sample function to generate a unique batch ID; can be modified as needed
    return 'batch_' . time();
}

function sendToCrossref(string $xml): string
{
    $options = kirby()->option('crossref', []);
    $url      = $options['apiUrl'] ?? 'https://api.crossref.org/deposits';
    $username = $options['username'] ?? null;
    $password = $options['password'] ?? null;
    $token    = $options['token'] ?? null;

    $ch = curl_init($url);
    $headers = [
        'Content-Type: application/xml'
    ];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    if ($username && $password) {
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    }

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return json_encode(['error' => $error]);
    }

    return $result ?: '';
}

?>
