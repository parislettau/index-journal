<?php

/** @var Kirby\Cms\Page $issue */
/** @var array $issueData */
/** @var array $essaysData */
?>
<section style="max-width:48rem;margin:2rem auto;font-family:system-ui">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">
        Confirm Crossref submission for “<?= html($issueData['issue_title']) ?>”
    </h1>

    <h2>Issue metadata</h2>
    <ul>
        <li><strong>DOI:</strong> <?= esc($issueData['doi']) ?></li>
        <li><strong>Date:</strong> <?= esc($issueData['issue_date']) ?></li>
        <li><strong>Number:</strong> <?= esc($issueData['issue_num']) ?></li>
        <li><strong>Year:</strong> <?= esc($issueData['year']) ?></li>
        <li><strong>URL:</strong> <a href="<?= esc($issueData['url']) ?>"><?= esc($issueData['url']) ?></a></li>
        <li><strong>Editors:</strong>
            <ul>
                <?php foreach ($issueData['editors'] as $e): ?>
                    <li><?= esc($e['name'] ?? (($e['first_name'] ?? '') . ' ' . ($e['last_name'] ?? ''))) ?></li>
                <?php endforeach ?>
            </ul>
        </li>
    </ul>

    <h2>Essays (<?= count($essaysData) ?>)</h2>
    <?php foreach ($essaysData as $essay): ?>
        <details style="margin-bottom:1rem">
            <summary style="cursor:pointer">
                <strong><?= esc($essay['title']) ?></strong>
                (<?= $essay['doi'] ? esc($essay['doi']) : 'no DOI' ?>)
            </summary>
            <div style="padding-left:1rem">
                <?php if ($essay['subtitle']): ?>
                    <p><strong>Subtitle:</strong> <?= esc($essay['subtitle']) ?></p>
                <?php endif ?>
                <p><strong>URL:</strong> <a href="<?= esc($essay['url']) ?>"><?= esc($essay['url']) ?></a></p>
                <?php if ($essay['abstract']): ?>
                    <p><strong>Abstract:</strong> <?= esc($essay['abstract']) ?></p>
                <?php endif ?>
                <p><strong>Year:</strong> <?= esc($essay['year']) ?></p>
                <p><strong>Authors:</strong></p>
                <ul>
                    <?php foreach ($essay['authors'] as $a): ?>
                        <li><?= esc($a['name'] ?? (($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''))) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </details>
    <?php endforeach ?>

    <form action="<?= url('submit-crossref/' . $issue->id()) ?>" method="post" style="display:flex;gap:.5rem;margin-top:2rem" id="crossref-submit-form">
        <?= csrf() ?>
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="k-button k-button--filled" id="crossref-submit-btn">Confirm</button>
        <a href="<?= $issue->panelUrl() ?>" class="k-button">Cancel</a>
    </form>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('crossref-submit-form');
        if (!form) return;
        form.addEventListener('submit', function () {
            var btn = document.getElementById('crossref-submit-btn');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Submitting…';
            }
        });
    });
    </script>
</section>