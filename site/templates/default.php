<?php snippet('header') ?>
<main data-template="<?= $page->template() ?>" class="pt-24 mx-auto max-w-3xl">
  <div class="text-block prose">
    <?= $page->text()->kirbytext() ?>
  </div>
</main>
<?php snippet('footer') ?>

