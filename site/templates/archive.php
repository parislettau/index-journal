<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>" class="prose mx-auto max-w-3xl pt-24">
  <?php foreach (page('issues')->children()->listed()->filterBy('issue_type', 'archive') as $issue) : ?>
    <?= $issue->title() ?>
  <?php endforeach ?>
</main>
<?php snippet('footer') ?>
</html>