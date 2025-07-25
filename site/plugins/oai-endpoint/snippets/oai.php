<?php
$site = kirby()->site();

$records = [];
$issues = $site->index()->filterBy('template', 'issue');
foreach ($issues as $issue) {
    $editors = [];
    foreach ($issue->editors()->toStructure() as $editor) {
        $editors[] = trim($editor->first_name() . ' ' . $editor->last_name());
    }
    $records[] = [
        'id'       => 'oai:' . $site->url() . ':' . $issue->id(),
        'date'     => $issue->modified('Y-m-d'),
        'title'    => $issue->title()->value(),
        'creators' => $editors,
        'doi'      => $issue->issue_doi()->value(),
        'abstract' => null,
        'url'      => $site->url() . '/' . $issue->id(),
        'subject'  => $issue->keywords()->value(),
        'publisher' => 'Index Journal',
        'pubDate'  => $issue->issue_date()->toDate('Y-m-d'),
        'type'     => 'Issue',
        'language' => 'en',
        'rights'   => 'CC BY-NC-ND 4.0',
    ];

    foreach ($issue->index()->filterBy('template', 'essay') as $essay) {
        $authors = [];
        foreach ($essay->authors()->toStructure() as $author) {
            $authors[] = trim($author->first_name() . ' ' . $author->last_name());
        }
        $records[] = [
            'id'       => 'oai:' . $site->url() . ':' . $essay->id(),
            'date'     => $essay->modified('Y-m-d'),
            'title'    => $essay->title()->value() . ($essay->subtitle()->isNotEmpty() ? ': ' . $essay->subtitle()->value() : ''),
            'creators' => $authors,
            'doi'      => $essay->doi()->value(),
            'abstract' => $essay->abstract()->value(),
            'url'      => $essay->url(),
            'subject'  => $essay->keywords()->value(),
            'publisher' => $essay->publisher()->or('Index Journal')->value(),
            'pubDate'  => $essay->parent()->issue_date()->toDate('Y-m-d'),
            'type'     => $essay->article_type()->or('ScholarlyArticle')->value(),
            'language' => $essay->language()->or('en')->value(),
            'rights'   => $essay->rights()->or('CC BY-NC-ND 4.0')->value(),
        ];
    }
}
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
    <responseDate><?= gmdate('Y-m-d\TH:i:s\Z'); ?></responseDate>
    <request verb="ListRecords" metadataPrefix="oai_dc"><?= $site->url() ?>/oai</request>
    <ListRecords>
        <?php foreach ($records as $r): ?>
            <record>
                <header>
                    <identifier><?= esc($r['id']) ?></identifier>
                    <datestamp><?= esc($r['date']) ?></datestamp>
                </header>
                <metadata>
                    <oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
                        xmlns:dc="http://purl.org/dc/elements/1.1/"
                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                        xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd">
                        <dc:title><?= esc($r['title']) ?></dc:title>
                        <?php foreach ($r['creators'] as $c): ?>
                            <dc:creator><?= esc($c) ?></dc:creator>
                        <?php endforeach; ?>
                        <?php if (!empty($r['subject'])): ?>
                            <dc:subject><?= esc($r['subject']) ?></dc:subject>
                        <?php endif; ?>
                        <?php if (!empty($r['publisher'])): ?>
                            <dc:publisher><?= esc($r['publisher']) ?></dc:publisher>
                        <?php endif; ?>
                        <?php if (!empty($r['pubDate'])): ?>
                            <dc:date><?= esc($r['pubDate']) ?></dc:date>
                        <?php endif; ?>
                        <?php if (!empty($r['type'])): ?>
                            <dc:type><?= esc($r['type']) ?></dc:type>
                        <?php endif; ?>
                        <?php if (!empty($r['language'])): ?>
                            <dc:language><?= esc($r['language']) ?></dc:language>
                        <?php endif; ?>
                        <?php if (!empty($r['rights'])): ?>
                            <dc:rights><?= esc($r['rights']) ?></dc:rights>
                        <?php endif; ?>
                        <?php if (!empty($r['abstract'])): ?>
                            <dc:description><?= esc($r['abstract']) ?></dc:description>
                        <?php endif; ?>
                        <?php if (!empty($r['doi'])): ?>
                            <dc:identifier><?= esc($r['doi']) ?></dc:identifier>
                        <?php endif; ?>
                        <dc:identifier><?= esc($r['url']) ?></dc:identifier>
                    </oai_dc:dc>
                </metadata>
            </record>
        <?php endforeach; ?>
    </ListRecords>
</OAI-PMH>