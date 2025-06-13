<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>" class="pt-24 mx-auto max-w-3xl">
  <div class="text-block prose">
    <?= $page->text()->kirbytext() ?>
    <div class="people flex flex-row mt-8 ml-16 max-w-[80%]">
      <?= $page->people()->kirbytext() ?>
    </div>
    <div class="colophon mt-16">
      <?= $page->colophon()->kirbytext() ?>
    </div>
    <div class="acknowledgement mt-9">
      <?= $page->acknowledgement()->kirbytext() ?>
    </div>
  </div>
</main>
<?php snippet('footer') ?>
</html>