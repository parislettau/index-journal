<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>">
  <header>
    <!-- Title -->
    <section class="title-block" style="">
      <h1 style="font-size:6vw; display:block" style="">
        <span class="title"><?= $page->title() ?></span>
      </h1>
    </section>
  </header>
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
<div class="flex m-4">
  <div class="flex gap-[12px]">
    <img src="https://assets.crossref.org/logo/crossref-logo-200.svg" alt="Crossref logo" style="width:auto; height:50px;">
    <img src="http://blog.doaj.org/wp-content/uploads/2024/02/DOAJ_Black.svg" alt="DOAJ logo" style="width:auto; height:50px;">
  </div>
</div>

<?php snippet('footer') ?>

</html>