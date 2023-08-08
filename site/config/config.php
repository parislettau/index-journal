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

  'floriankarsten.plausible' => [
    'sharedLink' => env('PLAUSIBLE_SHARED_LINK'),
    // 'domain' => 'test.com' // not required if not set it will be taken from $site->url
  ],

  // 'debug' => true,
  'debug' => false,

  # https://getkirby.com/docs/reference/system/options/panel
  // 'panel' => [
  //   'install' => true
  // ],

  'markdown' => [
    'extra' => true
  ],
  'panel.install' => true,
  'diesdasdigital.meta-knight' => [
    'siteTitleAfterPageTitle' => false,
  ],
  'hooks' => [
    'page.update:after' => function ($newPage, $oldPage) {
      output_pdf($newPage);
    }
  ],

  // https://github.com/medienbaecker/kirby-autoresize
  'medienbaecker.autoresize.maxWidth' => 3000,

  # https://getkirby.com/docs/cookbook/performance/responsive-images
  'thumbs' => [
    'srcsets' => [
      'default' => [
        '300w'  => ['width' => 300],
        '600w'  => ['width' => 600],
        '900w'  => ['width' => 900],
        '1200w' => ['width' => 1200],
        '1800w' => ['width' => 1800]
      ],
      'avif' => [
        '300w'  => ['width' => 300, 'format' => 'avif'],
        '600w'  => ['width' => 600, 'format' => 'avif'],
        '900w'  => ['width' => 900, 'format' => 'avif'],
        '1200w' => ['width' => 1200, 'format' => 'avif'],
        '1800w' => ['width' => 1800, 'format' => 'avif']
      ],
      'webp' => [
        '300w'  => ['width' => 300, 'format' => 'webp'],
        '600w'  => ['width' => 600, 'format' => 'webp'],
        '900w'  => ['width' => 900, 'format' => 'webp'],
        '1200w' => ['width' => 1200, 'format' => 'webp'],
        '1800w' => ['width' => 1800, 'format' => 'webp']
      ],
    ]
  ]

];
