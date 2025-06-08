<?php


function output_pdf($newPage)
{
  if ($newPage->template() == 'essay') :
    $pdf_name = $newPage->slug() . '.pdf';
    $outfile = $newPage->contentFileDirectory() . '/' . $pdf_name;
    $url = $newPage->previewUrl();
    exec('google-chrome --headless --print-to-pdf="' . $outfile . '" ' . $url . ' > /dev/null 2>/dev/null &');
  // exec('google-chrome-stable --headless --print-to-pdf="' . $outfile . '" ' . $url . ' > /dev/null 2>/dev/null &');
  endif;
}

# https://github.com/johannschopplich/kirby-helpers
// load dotenv plugins class so getenv can be used outside of closures
$base = dirname(__DIR__, 2);
\JohannSchopplich\Helpers\Env::load($base);

return [
  'url' => env('URL'),

  'ready' => function ($kirby) {
    return [
      'pechente.kirby-admin-bar' => [
        'active' => $kirby->user() !== null
      ]
    ];
  },
  // 'floriankarsten.plausible' => [
  //   'sharedLink' => env('PLAUSIBLE_SHARED_LINK'),
  //   // 'domain' => 'test.com' // not required if not set it will be taken from $site->url
  // ],
  # https://plugins.andkindness.com/seo/docs/get-started/installation-setup
  'tobimori.seo.canonicalBase' => env('URL'),
  'tobimori.seo.lang' => 'en-AU',

  # https://github.com/bnomei/kirby3-pageviewcounter#readme
  'bnomei.pageviewcounter.field.count' => 'viewcount',
  'bnomei.pageviewcounter.field.timestamp' => 'lastvisited',
  'bnomei.pageviewcounter.counter' => function () {
    return new \Bnomei\PageViewCounterField();
  },


  // 'debug' => true,
  'debug' => true,

  'tobimori.seo.robots' => [
    'active' => true,
    'followPageStatus' => false, // Prevent auto-blocking of drafts/unlisted
    'index' => true, // Allow indexing of all published pages
    // 'sitemap' => 'https://www.index-journal.org/sitemap.xml',
    'content' => [
      '*' => [
        'Allow' => ['/'],
        'Disallow' => ['/kirby', '/panel', '/content']
      ]
    ]
  ],

  # https://getkirby.com/docs/reference/system/options/panel
  // 'panel' => [
  //   'install' => true
  // ],

  'markdown' => [
    'extra' => true
  ],

  'panel.install' => true,

  'auth' => [
    'methods' => ['password', 'code']
  ],

  'diesdasdigital.meta-knight' => [
    'siteTitleAfterPageTitle' => true,
  ],
  'hooks' => [
    'page.update:after' => function ($newPage, $oldPage) {
      // output_pdf($newPage);
    }
  ],

  // https://github.com/medienbaecker/kirby-autoresize
  // 'medienbaecker.autoresize.maxWidth' => 3000,

  # https://getkirby.com/docs/cookbook/performance/responsive-images
  'thumbs' => [
    'srcsets' => [
      'default' => [
        '300w'  => ['width' => 300],
        '600w'  => ['width' => 600],
        '1800w' => ['width' => 1800]
      ],
      'avif' => [
        '300w'  => ['width' => 300, 'format' => 'avif'],
        '600w'  => ['width' => 600, 'format' => 'avif'],
        '1800w' => ['width' => 1800, 'format' => 'avif']
      ],
      'webp' => [
        '300w'  => ['width' => 300, 'format' => 'webp'],
        '600w'  => ['width' => 600, 'format' => 'webp'],
        '1800w' => ['width' => 1800, 'format' => 'webp']
      ],
    ]
  ],

  'routes' => [
    [
      'pattern' => '/books/(:any)',
      'action'  => function ($uid) {
        return go("https://index-press.com/", 301);
      }
    ],
    [
      'pattern' => 'citation/bibtex/(:all)',
      'action'  => function ($id) {
        if (!$page = page($id)) {
          return new Kirby\Cms\Response('Page not found', 'text/plain', 404);
        }
        return new Kirby\Cms\Response(citationBibtex($page), 'text/x-bibtex');
      }
    ],
    [
      'pattern' => 'citation/ris/(:all)',
      'action'  => function ($id) {
        if (!$page = page($id)) {
          return new Kirby\Cms\Response('Page not found', 'text/plain', 404);
        }
        return new Kirby\Cms\Response(citationRis($page), 'application/x-research-info-systems');
      }
    ],
    [
      'pattern' => 'oai',
      'action'  => function () {
        $xml = snippet('oai', [], true);
        return new Kirby\Cms\Response($xml, 'text/xml');
      }
    ],

  ],



  # https://getkirby.com/docs/reference/system/options/email
  'email' => [
    'transport' => [
      'type' => 'smtp',
      'host' => 'smtp.sendgrid.net',
      'port' => 465,
      'security' => true,
      'auth' => true,
      'username' => 'apikey',
      'password' => env('SENDGRID_PASSWORD'),

    ]
  ],


];
