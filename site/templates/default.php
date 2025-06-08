<?php snippet('header') ?>
<main data-template="<?= $page->template() ?>">
  <div class="text-block">
    <?= $page->text()->kirbytext() ?>
  </div>
</main>
<?php snippet('footer') ?>

