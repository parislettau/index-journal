<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>">
  <div class="text-block">
    <?= $page->text()->kirbytext() ?>
    <div class="people">
      <?= $page->people()->kirbytext() ?>
    </div>
    <div class="colophon">
      <?= $page->colophon()->kirbytext() ?>
    </div>
    <div class="acknowledgement">
      <?= $page->acknowledgement()->kirbytext() ?>
    </div>
  </div>
</main>
<?php snippet('footer') ?>
</html>