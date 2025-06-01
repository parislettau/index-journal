<?php snippet('header') ?>

<main>
  <?php $sections = $page->cfp_builder()->toStructure() ?>
  <?php foreach ($sections as $section) : ?>
    <section class="initial ">
      <?= $section->message()->kirbytext() ?>
    </section>

    <figure class="wipe" style="background-image: url('<?= $section->wipe_img()->toFile()->url() ?>');"></figure>
    <section class="spacer"></section>

    <div class="text-block">
      <section class="call">
        <?= $section->secondary_text()->kirbytext() ?>

        <section class="title-block">
          <span class="title-block-issue">Issue No.<?= $page->num() ?> <span class="title"><?= $page->title() ?></span></span>
          <div class="issue-items">
            <div class="issue-essays">
              <ul>
                <?php if ($page->hasChildren()) : ?>


                  <li><?php if ($page->hasDocuments()) : ?><span class="title">(<a href="<?= $page->documents()->first()->url() ?>" target="_blank">PDF</a>)</span><?php endif ?> </li>
                  <?php foreach ($page->children()->listed()->template('essay') as $article) : ?>

                    <li><a href="<?= $article->url() ?>"><span class="title"><?= $article->title() ?></span><?php if ($article->subtitle()->isNotEmpty()) : ?><span class="essay-subtitle"><?= $article->subtitle()->kti() ?></span><?php endif ?> by <?= $article->author() ?></a></li>

                  <?php endforeach ?>
                <?php endif ?>
              </ul>
            </div>


            <!--START OF SECTIONS-->
            <div class="issue-sections">
              <!--         IF ISSUE HAS SECTIONS, GET EACH SECTION OF THE ISSUE-->
              <?php if ($page->hasChildren()) : ?>
                <?php foreach ($page->children()->listed()->template('section') as $section) : ?>
                  <div class="title-block-section" style=""><span class="title"><?= $section->title() ?></span></div>
                  <ul>
                    <!--         IF SECTION HAS ARTICLES, GET EACH ARTICLE FROM SECTION-->
                    <?php if ($section->hasChildren()) : ?>
                      <?php foreach ($section->children()->listed() as $article) : ?>
                        <li><a href="<?= $article->url() ?>"><span class="title"><?= $article->title() ?></span><?php if ($article->subtitle()->isNotEmpty()) : ?><span class="section-subtitle"><?= $article->subtitle()->kti() ?></span><?php endif ?> by <?= $article->author() ?></a></li>
                      <?php endforeach ?>
                    <?php endif ?>
                  </ul>
                <?php endforeach ?>
              <?php endif ?>
            </div>
            <!--END OF SECTIONS-->
          </div>
        </section>
      </section>

      <div class="meta-container">

        <div class="issue-doi">
          <h2 style="margin:0;max-width:unset"> <?= $page->issue_doi() ?></h2>

        </div>

        <div class="issue-issue">
          <h2 style="margin:0;max-width:unset"><?= $page->issue_date()->toDate('d F Y') ?></h2>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</main>
<div class="issue-overlay">
  <span class="issue-overlay-close">(close)</span>
  <img src="<?= $page->issue_image()->toFile()->url() ?>" alt="">
  <?php $sections = $page->cfp_builder()->toStructure() ?>
  <figcaption style="text-align: center;max-width: 78%;">
    <?php foreach ($sections as $section) : ?><?= $section->wipe_img_caption() ?> <?php endforeach ?>
  </figcaption>
</div>
<!-- <h2 style="position:fixed; bottom:1rem;left:1rem;margin:0;width: 100%;">
  <?php if ($kirby->user()) : ?>
    <a href="#" id="downloadXML" class="custom-download-btn" style="background: black;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 127px;">Download CrossRef XML</a>
  <?php endif; ?>
</h2> -->
<script>
  document.getElementById('downloadXML').addEventListener('click', function(e) {
    e.preventDefault(); // prevent the default action
    window.location.href = '/generate-xml/<?= $page->id() ?>';
  });
</script>
<?php snippet('footer') ?>

</html>