<?php
/** @var Kirby\Cms\Page $issue */
/** @var array $articles */
?>
<section>
    <h1 style="font-size:1.5rem;margin-bottom:1rem">
        Confirm DOAJ bulk submission for “<?= html($issue->title()) ?>”
    </h1>
    <p><?= count($articles) ?> article(s) will be submitted.</p>
    <?php foreach ($articles as $data): ?>
        <details style="margin-bottom:1rem">
            <summary style="cursor:pointer">
                <strong><?= esc($data['bibjson']['title'] ?? '') ?></strong>
                <?php
                    $doi = '';
                    foreach (($data['bibjson']['identifier'] ?? []) as $id) {
                        if (($id['type'] ?? '') === 'doi') {
                            $doi = $id['id'];
                            break;
                        }
                    }
                    if ($doi): ?>
                        (<?= esc($doi) ?>)
                <?php endif; ?>
            </summary>
            <div style="padding-left:1rem">
                <p><strong>URL:</strong> <a href="<?= esc($data['bibjson']['link'][0]['url'] ?? '') ?>"><?= esc($data['bibjson']['link'][0]['url'] ?? '') ?></a></p>
                <p><strong>Year:</strong> <?= esc($data['bibjson']['year'] ?? '') ?></p>
            </div>
        </details>
    <?php endforeach ?>
    <form action="<?= url('submit-doaj-bulk/' . $issue->id()) ?>" method="post" style="display:flex;gap:.5rem;margin-top:2rem" id="doaj-bulk-submit-form">
        <?= csrf() ?>
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="k-button k-button--filled" id="doaj-bulk-submit-btn">Confirm</button>
        <a href="<?= $issue->panelUrl() ?>" class="k-button">Cancel</a>
    </form>
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
