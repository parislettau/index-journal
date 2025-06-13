<?php snippet('header') ?>

<div class="introduction prose mx-auto max-w-3xl mt-24">
  <?= $page->intro()->kirbytext() ?>
</div>
<main data-template="<?= $page->template() ?>" class="mt-12 prose mx-auto max-w-3xl">

  <ul>
    <?php foreach (page('emaj')->children()->listed() as $issue) : ?>
      <div class="issue-items">
        <li><a href="<?= $issue->url() ?>"><?= $issue->title() ?></a>
          <ul>
            <?php foreach ($issue->children()->listed() as $article) : ?>
              <li><a href="<?= $article->url() ?>"><?= $article->title() ?> <span class="authors">by <?= $article->author() ?></span></a></li>
            <?php endforeach ?>
          </ul>
        </li>
      </div>
    <?php endforeach ?>
  </ul>
  <p style="text-indent: 0; margin-top: 2.2em;">(ISSN (elec): <?= $page->issn() ?>, DOI: <a href="<?= $page->doi() ?>"><?= $page->doi() ?></a>)</p>
</main>