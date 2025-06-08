<!DOCTYPE html>
<html lang="<?= $site->lang() ?>" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- https://plugins.andkindness.com/seo/docs/get-started/installation-setup -->
  <?php snippet('seo/head'); ?>
  <?= $page->meta()->opensearch() ?>

  <!-- Google Scholar -->
  <?php if ($page->template() == "essay") : ?>
    <!-- https://scholar.google.com/intl/en/scholar/inclusion.html#indexing -->
    <meta name="citation_title" content="<?= $page->title()->kirbytextinline() . ($page->subtitle()->isNotEmpty() ? ': ' . $page->subtitle()->kirbytextinline() : '') ?>">

    <?php foreach ($page->authors()->yaml() as $author) : ?>
      <meta name="citation_author" content="<?= $author['last_name'] ?>, <?= $author['first_name'] ?>">
    <?php endforeach ?>
    <meta name="citation_publication_date" content="<?= $page->parent()->issue_date()->toDate('Y/m/d') ?>">
    <meta name="citation_journal_title" content="<?= $site->title() ?>">
    <meta name="citation_volume" content="<?= $page->parent()->issue_date()->toDate('Y') ?>">
    <meta name="citation_issue" content="<?= $page->parent()->num() ?>">
    <?php if ($page->documents()->isNotEmpty()) : ?>
      <meta name="citation_pdf_url" content="<?= $page->documents()->first()->url() ?>">
    <?php endif; ?>
    <?php if ($page->doi()->isNotEmpty()) : ?>
      <meta name="citation_doi" content="<?= $page->doi() ?>">
    <?php endif ?>
  <?php endif ?>

  <!-- plausible -->
  <script defer data-domain="index-journal.org" src="https://plausible.memoreview.net/js/script.js"></script>

  <!-- facivon -->
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">

  <!-- css -->
  <?= css('assets/css/style.css?v=' . sha1_file('assets/css/style.css')) ?>
  <?= css('assets/css/print.css?v=' . sha1_file('assets/css/print.css')) ?>
  <?= css('assets/css/tailwind.css?v=' . sha1_file('assets/css/tailwind.css')) ?>

  <!-- js -->
  <?= js('assets/js/jquery.min.js') ?>
  <?= js('assets/js/images.js') ?>
  <?= js('assets/js/script.js?v=' . sha1_file('assets/js/script.js')) ?>

  <!-- google analytics -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google-site-verification" content="REP3FyJ9Bqjr3A2K2owDTv9toyWnGTjnoPxmUs6vGT4" />
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139982363-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-139982363-1');
  </script>

  <!-- Matomo -->
  <script>
    var _paq = window._paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
      var u = "//matomo.memoreview.net/";
      _paq.push(['setTrackerUrl', u + 'matomo.php']);
      _paq.push(['setSiteId', '3']);
      var d = document,
        g = d.createElement('script'),
        s = d.getElementsByTagName('script')[0];
      g.async = true;
      g.src = u + 'matomo.js';
      s.parentNode.insertBefore(g, s);
    })();
  </script>
  <!-- End Matomo Code -->
</head>

<body data-display="<?= $page->template() ?>">
  <header class="index-header" <?php if ($page->template() == 'essay') : ?>style="background-color: rgb(<?= $page->parent()->issue_color() ?>); box-shadow: 0px 11px 16px 0px rgba(<?= $page->parent()->issue_color() ?>);" <?php endif ?>>
    <h1>
      <a href="<?= $site->url() ?>">INDEX JOURNAL</a>


      <?php foreach (page('issues')->children()->listed()->flip()->slice(0, 1) as $issue) : ?>
        <a href="<?= $issue->url() ?>" class="current-issue"><span>, Issue </span><span>No. </span><?= $issue->num() ?><span style="text-transform: uppercase;"> <?= $issue->title() ?></span></a>
      <?php endforeach ?>


    </h1>

    <nav class="menu" style="cursor:pointer">
      <span>Menu</span>
    </nav>
  </header>

  <?php snippet('menu-pane'); ?>