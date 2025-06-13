<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!-- https://github.com/diesdasdigital/kirby-meta-knight -->
  <?php snippet('meta_information'); ?>
  <?php snippet('robots'); ?>

  <!-- plausible -->
  <script defer data-domain="index-journal.org" src="https://plausible.memoreview.net/js/script.js"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>INDEX BOOKS</title>
  <?php snippet('meta_information'); ?>
  <?php snippet('robots'); ?>
  <?= css('assets/css/style.css?v=' . sha1_file('assets/css/style.css')) ?>
  <?= css('assets/css/print.css?v=' . sha1_file('assets/css/print.css')) ?>
  <?= css('assets/css/lightbox.css?v=' . sha1_file('assets/css/lightbox.css')) ?>
  <?= js('assets/js/jquery.min.js') ?>
  <?= js('assets/js/images.js') ?>
  <?= js('assets/js/lightbox.js') ?>
  <?= js('assets/js/script.js?v=' . sha1_file('assets/js/script.js')) ?>
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
  <script src="https://unpkg.com/loadeer" defer init></script>
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
  <script>
    window.SnipcartSettings = {
      publicApiKey: 'M2E1NDU1M2EtMGU4OS00ZTVkLWFlOWMtNWY2NDZjNGVjNTdlNjM3NTA4NDM4NzI0Mjg5NTA4',
      loadStrategy: 'on-user-interaction',
      // modalStyle: "side",
      // loadCSS: false,
    };

    (() => {
      var c, d;
      (d = (c = window.SnipcartSettings).version) != null || (c.version = "3.0");
      var s, S;
      (S = (s = window.SnipcartSettings).timeoutDuration) != null || (s.timeoutDuration = 2750);
      var l, p;
      (p = (l = window.SnipcartSettings).domain) != null || (l.domain = "cdn.snipcart.com");
      var w, u;
      (u = (w = window.SnipcartSettings).protocol) != null || (w.protocol = "https");
      var f = window.SnipcartSettings.version.includes("v3.0.0-ci") || window.SnipcartSettings.version != "3.0" && window.SnipcartSettings.version.localeCompare("3.4.0", void 0, {
          numeric: !0,
          sensitivity: "base"
        }) === -1,
        m = ["focus", "mouseover", "touchmove", "scroll", "keydown"];
      window.LoadSnipcart = o;
      document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", r) : r();

      function r() {
        window.SnipcartSettings.loadStrategy ? window.SnipcartSettings.loadStrategy === "on-user-interaction" && (m.forEach(t => document.addEventListener(t, o)), setTimeout(o, window.SnipcartSettings.timeoutDuration)) : o()
      }
      var a = !1;

      function o() {
        if (a) return;
        a = !0;
        let t = document.getElementsByTagName("head")[0],
          e = document.querySelector("#snipcart"),
          i = document.querySelector(`src[src^="${window.SnipcartSettings.protocol}://${window.SnipcartSettings.domain}"][src$="snipcart.js"]`),
          n = document.querySelector(`link[href^="${window.SnipcartSettings.protocol}://${window.SnipcartSettings.domain}"][href$="snipcart.css"]`);
        e || (e = document.createElement("div"), e.id = "snipcart", e.setAttribute("hidden", "true"), document.body.appendChild(e)), v(e), i || (i = document.createElement("script"), i.src = `${window.SnipcartSettings.protocol}://${window.SnipcartSettings.domain}/themes/v${window.SnipcartSettings.version}/default/snipcart.js`, i.async = !0, t.appendChild(i)), n || (n = document.createElement("link"), n.rel = "stylesheet", n.type = "text/css", n.href = `${window.SnipcartSettings.protocol}://${window.SnipcartSettings.domain}/themes/v${window.SnipcartSettings.version}/default/snipcart.css`, t.prepend(n)), m.forEach(g => document.removeEventListener(g, o))
      }

      function v(t) {
        !f || (t.dataset.apiKey = window.SnipcartSettings.publicApiKey, window.SnipcartSettings.addProductBehavior && (t.dataset.configAddProductBehavior = window.SnipcartSettings.addProductBehavior), window.SnipcartSettings.modalStyle && (t.dataset.configModalStyle = window.SnipcartSettings.modalStyle), window.SnipcartSettings.currency && (t.dataset.currency = window.SnipcartSettings.currency), window.SnipcartSettings.templatesUrl && (t.dataset.templatesUrl = window.SnipcartSettings.templatesUrl))
      }
    })();
  </script>

  <header class="flex justify-between fixed left-0 w-screen p-4 bg-white shadow-[0px_15px_16px_0px_rgba(255,255,255,1)] transition z-10" style="background-color: <?= $color ?>; box-shadow: 0px 15px 16px 0px <?= $color ?>; top: var(--admin-bar--height, 0);">
    <h1>
      <a href="<?= $site->url() ?>/books">INDEX<span> BOOKS</span></a>,
      <nav class="inline">

        <!-- <a href="/books/about" class="no-mobile">ABOUT, </a> -->
        <a class="snipcart-checkout">CART</a> (<span class="snipcart-items-count "></span>, <span class="snipcart-total-price"></span>)
      </nav>
    </h1>

    <nav class="menu cursor-pointer">
      <span>Menu</span>
    </nav>
  </header>
  <?php snippet('menu-pane'); ?>