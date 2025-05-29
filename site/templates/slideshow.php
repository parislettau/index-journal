<?php snippet('header') ?>
<?php echo $page->counterCss(); ?>
<main data-template="<?= $page->template() ?>" class="snap-y snap-mandatory overflow-y-scroll h-screen">
  <!-- Title -->
  <section class="title-block snap-start" style="background-color: rgb(<?= $page->parent()->issue_color() ?>)">
    <h1 style="font-size:6vw">
      <span class="title"><?= $page->title() ?></span>
      <?php if ($page->subtitle()->isNotEmpty()) : ?>
        <span class="subtitle"><?= smartypants($page->subtitle()->kti()) ?></span>
      <?php endif ?>
      <?php if ($page->slug() != 'introduction') : ?>
        <span class="author">by <?= $page->author() ?></span>
      <?php endif ?>
    </h1>
  </section>

  <!-- slideshow -->
  <section class="snap-start h-full">
    <div class="whitespace-nowrap p-3 overflow-x-auto space-x-4 flex">
      <?php if ($page->slideshow()->isNotEmpty()): ?>
        <?php foreach ($page->slideshow()->toFiles() as $image): ?>
          <img src="<?= $image->url() ?>" alt="<?= $image->alt()->or('Image') ?>" class="py-24 h-[100vh] m-w-full object-cover rounded-lg">
        <?php endforeach ?>
      <?php endif ?>

    </div>
  </section>

  <!-- Text -->
  <div class="text-block snap-start">

    <!-- content -->
    <div class="content-block">
      <?= smartypants($page->text()->kirbytext()) ?>
    </div>
  </div>

  <!-- Credit -->
  <div class="snap-start text-xs p-10 leading-tight h-full pt-24">
    <?= smartypants($page->credit()->kirbytext()) ?>
  </div>

  <!-- doi -->
  <?php if ($page->slug() != 'introduction') : ?>
    <span class="essay-extra">
      <?php if ($page->doi()->isNotEmpty()) : ?>
        <span class="doi">
          <a href="http://doi.org/<?= $page->doi() ?>"><?= $page->doi() ?></a>
        </span>
      <?php endif ?>
    </span>
<?php endif ?>
</main>
<?php snippet('footer') ?>
</html>