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
<div class="flex justify-end m-[12px]">
  <div class="flex gap-[12px]">
    <img src="https://assets.crossref.org/logo/crossref-logo-200.svg" alt="Crossref logo" style="width:auto; height:50px;">
    <img src="http://blog.doaj.org/wp-content/uploads/2024/02/DOAJ_Black.svg" alt="DOAJ logo" style="width:auto; height:50px;">

  </div>
</div>

<?php snippet('footer') ?>

</html>