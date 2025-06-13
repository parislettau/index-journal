<?php snippet('header') ?>

<main class="prose mx-auto max-w-3xl pt-24">
  <?php if ($page->show_hide() == 'true') : ?>
    <?php $sections = $page->cfp_builder()->toStructure() ?>
    <?php foreach ($sections as $section) : ?>
      <section class="initial">
        <?= $section->message()->kirbytext() ?>
      </section>

      <figure class="wipe" style="background-image: url('<?= $section->wipe_img()->toFile()->url() ?>');"></figure>
      <section class="spacer"></section>

      <div class="text-block">
        <section class="call">
          <?= $section->secondary_text()->kirbytext() ?>
          <p style="text-indent: 0; text-align: center; max-width: 80%; font-size: 90%; margin: 1em auto; position: absolute; bottom: 0.5rem;font-size: 0.65rem"><?= $section->wipe_img_caption() ?></p>
        </section>
      </div>
    <?php endforeach ?>
  <?php else : ?>
    <?php foreach (page('issues')->children()->listed()->flip()->limit(1) as $home) : ?>
      <?php $sections = $home->cfp_builder()->toStructure() ?>
      <?php foreach ($sections as $section) : ?>
        <section class="initial">
          <?= $section->message()->kirbytext() ?>
        </section>

        <figure class="wipe" style="background-image: url('<?= $section->wipe_img()->toFile()->url() ?>');"></figure>
        <section class="spacer"></section>

        <div class="text-block">
          <section class="call">
            <?= $section->secondary_text()->kirbytext() ?>

            <section class="title-block">
              <span class="title-block-issue">Issue No.<?= $home->num() ?> <span class="title"><?= $home->title() ?></span></span>
              <div class="issue-items">
                <ul>
                  <?php if ($home->hasChildren()) : ?>
                    <?php foreach ($home->children()->listed() as $article) : ?>
                      <li><a href="<?= $article->url() ?>"><span class="title"><?= $article->title() ?></span> by <?= $article->author() ?></a></li>
                    <?php endforeach ?>
                  <?php endif ?>
                </ul>
              </div>
            </section>
            <p style="text-indent: 0; text-align: center; max-width: 80%; font-size: 90%; margin: 1em auto; position: absolute; bottom: 0.5rem;font-size: 0.65rem"><?= $section->wipe_img_caption() ?></p>
          </section>
        </div>
      <?php endforeach ?>
      <div class="issue-overlay">
        <span class="issue-overlay-close">(close)</span>
        <img src="<?= $home->issue_image()->toFile()->url() ?>" alt="">
      </div>
    <?php endforeach ?>
  <?php endif ?>


</main>
<div class="issue-overlay">
  <span class="issue-overlay-close">(close)</span>
  <img src="<?= $page->wipe_img_full()->toFile()->url() ?>" alt="">
  <figcaption style="text-align: center;max-width: 78%;">
    <?= $page->wipe_img_caption() ?>
</figcaption>
</div>
<?php snippet('footer') ?>
</html>