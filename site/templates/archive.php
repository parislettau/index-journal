<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>">
  <?php foreach (page('issues')->children()->listed()->filterBy('issue_type', 'archive') as $issue) : ?>
    <?= $issue->title() ?>
  <?php endforeach ?>
</main>
<?php snippet('footer') ?>
</html>