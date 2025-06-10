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
  </div>
</main>
<?php snippet('footer') ?>