<?php snippet('header') ?>
<?php echo $page->counterCss(); ?>

<main data-template="<?= $page->template() ?>">
  <article>
    <header>
      <h1 style="display:block"><span class="title"><?= $page->title() ?></span>
        <?php if ($page->subtitle()->isNotEmpty()) : ?><span class="subtitle"><?= smartypants($page->subtitle()->kti()) ?></span><?php endif ?>
        <?php if ($page->slug() != 'introduction') : ?><span class="author">by <?= $page->author() ?></span><?php endif ?>
      </h1>
    </header>

    <section>

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


    </section>

    <footer>
      <?php if ($page->slug() != 'introduction') : ?>
        <span class="essay-extra">
          <?php if ($page->doi()->isNotEmpty()) : ?>
            <span class="doi">DOI: <a href="https://doi.org/<?= $page->doi() ?>"><?= $page->doi() ?></a></span>
          <?php endif ?>
          <?php if ($page->hasDocuments()) : ?>
            <span class="pdf"><a href="<?= $page->documents()->first()->url() ?>" target="_blank">PDF</a></span>
          <?php endif ?>
          <span class="citation">
            <a href="<?= url('citation/bibtex/' . $page->id()) ?>">BibTeX</a> |
            <a href="<?= url('citation/ris/' . $page->id()) ?>">RIS</a>
          </span>
        </span>
      <?php endif ?>
    </footer>
    <?php snippet('how-to-cite', ['page' => $page]) ?>
  </article>

</main>
<?php snippet('footer') ?>

</html>