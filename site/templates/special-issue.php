<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>" class="snap-y snap-mandatory overflow-y-scroll h-screen">
  <!-- Title -->
  <section class="title-block snap-start" style="background-color: rgb(<?= $page->parent()->issue_color() ?>)">
    <h1 style="font-size:6vw">
      <span class="title"><?= $page->title() ?></span>
      <?php if ($page->slug() != 'introduction') : ?>
        <span class="author">
          <div class="editor-list text-center">

            <span>Edited by</span><?php foreach ($page->editors()->toStructure() as $index => $editor): ?><span class="editor-name first:before:content-[''] after:content-[','] pt-0 last:after:content-[''] "> <?= $editor->first_name() ?>&nbsp;<?= $editor->last_name() ?></span><?php endforeach; ?>
          </div>


        </span>
      <?php endif ?>
    </h1>
  </section>

  <!-- slideshow -->
  <section class="snap-start h-full pt-24">


    <ul>
      <?php if ($page->hasChildren()) : ?>

        <?php foreach ($page->children()->listed() as $article) : ?>

          <li><a href="<?= $article->url() ?>"><span class="title"><?= $article->title() ?></span><?php if ($article->subtitle()->isNotEmpty()) : ?><span class="essay-subtitle"><?= $article->subtitle()->kti() ?></span><?php endif ?> by <?= $article->author() ?></a></li>

        <?php endforeach ?>
      <?php endif ?>
    </ul>


  </section>

  <!-- Text -->
  <div class="text-block snap-start h-full pt-24">
    <div class="content-block">
      <?= smartypants($page->text()->kirbytext()) ?>
    </div>
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

<h2 style="position:fixed; bottom:1rem;left:1rem;margin:0;width: 100%;">
  <?php if ($kirby->user()) : ?>
    <a href="#" id="downloadXML" class="custom-download-btn" style="background: black;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 127px;">Download CrossRef XML</a>
  <?php endif; ?>
</h2>
<script>
  document.getElementById('downloadXML').addEventListener('click', function(e) {
    e.preventDefault(); // prevent the default action
window.location.href = '/generate-xml/<?= $page->id() ?>';
});
</script>
<?php snippet('footer') ?>
</html>