<?php
/** @var Kirby\Cms\Page $essay */
/** @var array $data */
?>
<section>
    <h1 style="font-size:1.5rem;margin-bottom:1rem">
        Confirm DOAJ submission for “<?= html($data['bibjson']['title']) ?>”
    </h1>
    <ul>
        <li><strong>DOI:</strong> <?= esc($data['bibjson']['identifier'][0]['id'] ?? '') ?></li>
        <li><strong>Year:</strong> <?= esc($data['bibjson']['year'] ?? '') ?></li>
        <li><strong>Month:</strong> <?= esc($data['bibjson']['month'] ?? '') ?></li>
        <li><strong>URL:</strong> <a href="<?= esc($data['bibjson']['link'][0]['url'] ?? '') ?>"><?= esc($data['bibjson']['link'][0]['url'] ?? '') ?></a></li>
        <li><strong>Journal:</strong> <?= esc($data['bibjson']['journal']['title'] ?? '') ?></li>
        <li><strong>ISSN:</strong> <?= esc($data['bibjson']['journal']['issn'] ?? '') ?></li>
    </ul>
    <h2>Authors</h2>
    <ul>
        <?php foreach ($data['bibjson']['author'] as $a): ?>
            <li><?= esc($a['name']) ?></li>
        <?php endforeach ?>
    </ul>
    <?php if (!empty($data['bibjson']['abstract'])): ?>
        <p><strong>Abstract:</strong> <?= esc($data['bibjson']['abstract']) ?></p>
    <?php endif ?>
    <form action="<?= url('submit-doaj/' . $essay->id()) ?>" method="post" style="display:flex;gap:.5rem;margin-top:2rem" id="doaj-submit-form">
        <?= csrf() ?>
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="k-button k-button--filled" id="doaj-submit-btn">Confirm</button>
        <a href="<?= $essay->panelUrl() ?>" class="k-button">Cancel</a>
    </form>
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
