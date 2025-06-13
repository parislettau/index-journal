<?php snippet('header') ?>

<main data-template="<?= $page->template() ?>" class="pt-24 mx-auto max-w-3xl">
  <div class="introduction prose">
    <figure>
      <!-- <img src="<?= $site->url() ?>/assets/images/02.png" alt=""> -->
      <div><?= $page->title() ?></div>
    </figure>
    <!-- <div class="header">
        <a href="<?= $page->parent()->url() ?>">(Issues)</a>
        <h2><?= $page->title() ?></h2>
      </div> -->
    <div class="details">
      <p>EDITORS: <?= $page->editors() ?></p>
      <p>ISSN (elec): <?= $page->parent()->issn() ?></p>
    </div>
  </div>
  <ul class="articles mt-8">
    <?php foreach ($page->children()->listed() as $article) : ?>
      <li><a href="<?= $article->url() ?>"><?= $article->title() ?> <span class="authors">by <?= $article->author() ?></span></a></li>
    <?php endforeach ?>
  </ul>
</main>