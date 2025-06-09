<?php

/** @var Kirby\Cms\Page $essay */
/** @var array $data */
/** @var array|null $existing */

$existingBib = $existing['bibjson'] ?? null;

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
        Confirm DOAJ submission for “<?= html($data['bibjson']['title']) ?>”
    </h1>
    <table style="border-collapse:collapse;margin-bottom:1rem;width:100%">
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
    <form action="<?= url('submit-doaj/' . $essay->id()) ?>" method="post" style="display:flex;gap:.5rem;margin-top:2rem" id="doaj-submit-form">
        <?= csrf() ?>
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="k-button k-button--filled" id="doaj-submit-btn">Confirm</button>
        <a href="<?= $essay->panelUrl() ?>" class="k-button">Cancel</a>
    </form>
    <h2>JSON Data</h2>
    <pre><?= json_encode($data['bibjson'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></pre>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('doaj-submit-form');
            if (!form) return;
            form.addEventListener('submit', function() {
                var btn = document.getElementById('doaj-submit-btn');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Submitting…';
                }
            });
        });
    </script>
</section>