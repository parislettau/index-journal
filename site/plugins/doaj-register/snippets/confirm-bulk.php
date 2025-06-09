<?php

/** @var Kirby\Cms\Page $issue */
/** @var array $articles */

$getDoi = function ($bib) {
    foreach (($bib['identifier'] ?? []) as $id) {
        if (($id['type'] ?? '') === 'doi') {
            return $id['id'];
        }
    }
    return '';
};

$getAuthors = function ($bib) {
    $names = [];
    foreach (($bib['author'] ?? []) as $a) {
        if (!empty($a['name'])) {
            $names[] = $a['name'];
        }
    }
    return implode('; ', $names);
};

$getUrl = fn($bib) => $bib['link'][0]['url'] ?? '';

$fields = [
    'Title'   => fn($b) => $b['title'] ?? '',
    'DOI'     => $getDoi,
    'Year'    => fn($b) => $b['year'] ?? '',
    'Month'   => fn($b) => $b['month'] ?? '',
    'URL'     => $getUrl,
    'Journal' => fn($b) => $b['journal']['title'] ?? '',
    'ISSN'    => fn($b) => $b['journal']['issn'] ?? '',
    'Authors' => $getAuthors,
    'Abstract'=> fn($b) => $b['abstract'] ?? '',
];
?>
<section>
    <h1 style="font-size:1.5rem;margin-bottom:1rem">
        Confirm DOAJ bulk submission for “<?= html($issue->title()) ?>”
    </h1>
    <p><?= count($articles) ?> article(s) will be submitted.</p>
    <?php foreach ($articles as $article):
        $data = $article['data'];
        $existing = $article['existing'];
        $existingBib = $existing['bibjson'] ?? null;
        $doi = $getDoi($data['bibjson']);
    ?>
        <details style="margin-bottom:1rem">
            <summary style="cursor:pointer">
                <strong><?= esc($data['bibjson']['title'] ?? '') ?></strong>
                <?php if ($doi): ?>(<?= esc($doi) ?>)<?php endif ?>
            </summary>
            <div style="padding-left:1rem">
                <table style="border-collapse:collapse;margin:0.5rem 0;width:100%">
                    <thead>
                        <tr>
                            <th style="text-align:left">Field</th>
                            <?php if ($existingBib): ?>
                                <th style="text-align:left">Current</th>
                            <?php endif ?>
                            <th style="text-align:left">New</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fields as $label => $fn):
                            $new = $fn($data['bibjson']);
                            $old = $existingBib ? $fn($existingBib) : '';
                            $changed = $existingBib && $old !== $new;
                        ?>
                            <tr<?= $changed ? ' style="background:#ffe6e6"' : '' ?>>
                                <td><strong><?= esc($label) ?></strong></td>
                                <?php if ($existingBib): ?>
                                    <td><?= esc($old) ?></td>
                                <?php endif ?>
                                <td><?= esc($new) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </details>
    <?php endforeach ?>
    <form action="<?= url('submit-doaj-bulk/' . $issue->id()) ?>" method="post" style="display:flex;gap:.5rem;margin-top:2rem" id="doaj-bulk-submit-form">
        <?= csrf() ?>
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="k-button k-button--filled" id="doaj-bulk-submit-btn">Confirm</button>
        <a href="<?= $issue->panelUrl() ?>" class="k-button">Cancel</a>
    </form>
    <h2>Preview DOAJ Payload</h2>
    <pre><?= json_encode(array_column($articles, 'data'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('doaj-bulk-submit-form');
            if (!form) return;
            form.addEventListener('submit', function() {
                var btn = document.getElementById('doaj-bulk-submit-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Submitting…';
                }
            });
        });
    </script>
</section>