<?php snippet('header') ?>
<?php echo $page->counterCss(); ?>
<main data-template="<?= $page->template() ?>">
  <article>
    <header>
      <!-- Title -->
      <section class="title-block" style="background-color: rgb(<?= $page->parent()->issue_color() ?>)">
        <h1 style="font-size:6vw; display:block" style="">
          <span class="title"><?= $page->title() ?></span>
          <?php if ($page->subtitle()->isNotEmpty()) : ?>
            <span class="subtitle"><?= smartypants($page->subtitle()->kti()) ?></span>
          <?php endif ?>
          <?php if ($page->slug() != 'introduction') : ?>
            <span class="author">by <?= $page->author() ?></span>
          <?php endif ?>
        </h1>
      </section>
    </header>

    <section>

      <!-- RIS etc -->
      <aside>
        <?php if ($page->slug() != 'introduction') : ?>
          <div class="text-[1.8rem] m-4">
            <?php if ($page->doi()->isNotEmpty()) : ?>
              <span class="doi">
                <a href="http://doi.org/<?= $page->doi() ?>"><?= $page->doi() ?></a>
              </span>
            <?php endif ?>
            <span class="citation">
              <a href="<?= url('citation/bibtex/' . $page->id()) ?>">BibTeX</a> |
              <a href="<?= url('citation/ris/' . $page->id()) ?>">RIS</a>
            </span>
            <?php if ($page->rights()->isNotEmpty()) : ?>
              <span class="rights"><?= $page->rights() ?></span>
            <?php endif ?>
            <?php if ($page->hasDocuments()) : ?>
              <span class=""> | <a href="<?= $page->documents()->first()->url() ?>" target="_blank" rel="noopener noreferrer">PDF</a></span>
            <?php endif ?>
          </div>

        <?php endif ?>
        <!-- abstract -->
        <?php if ($page->abstract()->isNotEmpty()): ?>
          <div class="abstract-block relative font-[DinSynt] mb-[1.3em] mt-[1em]  max-w-[55%] max-[670px]:ml-0 max-[670px]:max-w-full max-[670px]:pr-4">
            <div id="abstractContent" class="relative overflow-hidden text-2x line-clamp-5 transition-all duration-300 ease-in-out m-4">
              <?= $page->abstract()->kirbytextinline() ?>
              <div id="abstractShadow" class="pointer-events-none absolute bottom-0 left-0 w-full h-[6.5em] z-10 bg-gradient-to-b from-transparent to-white"></div>
            </div>
            <div id="toggleAbstract" type="button" class="ml-4 text-sm underline cursor-pointer">
              Read full abstract
            </div>
          </div>

          <script>
            const toggleBtn = document.getElementById('toggleAbstract');
            const content = document.getElementById('abstractContent');
            const shadow = document.getElementById('abstractShadow');

            toggleBtn.addEventListener('click', () => {
              const isCollapsed = content.classList.toggle('line-clamp-5');
              shadow.classList.toggle('hidden');
              toggleBtn.textContent = isCollapsed ? 'Read full abstract' : 'Collapse';
            });
          </script>
        <?php endif ?>
      </aside>



      <!-- Text -->

      <div class="text-block">
        <!-- content -->
        <div class="content-block">
          <?= smartypants($page->text()->kirbytext()) ?>
        </div>
      </div>

      <!-- Bios -->
      <div class="text-bios">
        <?= smartypants($page->bios()->kirbytext()) ?>
      </div>

      <!-- bibliographies -->
      <?php if ($page->bibilography()->isNotEmpty()) : ?>
        <p style="font-size: 1.4rem; text-indent: 0; text-transform: uppercase; margin-bottom: 1em; padding: 0 4em;">Bibliography</p>
        <div class="text-bibliography">
          <?= smartypants($page->bibilography()->kirbytext()) ?>
        </div>
      <?php endif ?>


      <!-- bibliographies -->
      <?php if ($page->selected_bibilography()->isNotEmpty()) : ?>
        <div class="wrapper-bib">
          <p style="font-size: 1.4rem; text-indent: 0; text-transform: uppercase; margin-bottom: 1em; padding: 0 4em; margin-top:4rem">
            Selected Bibliography
          </p>
          <div class=" selected-bibliography">
            <?= $page->headnote()->kirbytext() ?>
          </div>
          <?php foreach ($page->selected_bibilography()->toStructure() as $section) : ?>
            <div class="selected-bibliography">
              <h2> <?= $section->heading() ?></h2>

            </div>
            <div class="text-bibliography">
              <?= $section->bibliography()->kirbytext() ?>
            </div>
          <?php endforeach ?>

        <?php endif ?>
        </div>

    </section>


  </article>
</main>
<?php snippet('footer') ?>

</html>