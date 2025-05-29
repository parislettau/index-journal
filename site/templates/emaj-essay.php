<?php snippet('header') ?>
<?php echo $page->counterCss(); ?>

<main data-template="<?= $page->template() ?>">

  <h1><span class="title"><?= $page->title() ?></span>
    <?php if ($page->subtitle()->isNotEmpty()) : ?><span class="subtitle"><?= smartypants($page->subtitle()->kti()) ?></span><?php endif ?>
    <?php if ($page->slug() != 'introduction') : ?><span class="author">by <?= $page->author() ?></span><?php endif ?>
  </h1>

  <div class="text-block">

    <?php if ($page->abstract()->isNotEmpty()) : ?>
      <div class="abstract-block">
        <div class="abstract-content">
          <div class="shadow"></div>
          <div class="abstract hidden"><?= smartypants($page->abstract()->kirbytext()) ?></div>
        </div>
      </div>
    <?php endif ?>

    <?= smartypants($page->text()->kirbytext()) ?>
    <div class="text-bios">
      <?= smartypants($page->bios()->kirbytext()) ?>
    </div>
    <div class="text-bibliography">
      <?= smartypants($page->bibilography()->kirbytext()) ?>
    </div>

    <?php if ($page->slug() != 'introduction') : ?><span class="essay-extra">(<?php if ($page->doi()->isNotEmpty()) : ?><span class="doi">DOI: <a href="https://doi.org/<?= $page->doi() ?>"><?= $page->doi() ?></span></a><?php endif ?><?php if ($page->hasDocuments()) : ?><span class="pdf"><a href="<?= $page->documents()->first()->url() ?>" target="_blank">PDF</a></span><?php endif ?>)</span><?php endif ?>
  </div>

</main>
<?php snippet('footer') ?>
</html>