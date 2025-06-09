<?php

use Kirby\Cms\Response;

Kirby::plugin('custom/oai-endpoint', [
    'snippets' => [
        'oai' => __DIR__ . '/snippets/oai.php'
    ],
    'routes' => [
        [
            'pattern' => 'oai',
            'action'  => function () {
                $xml = snippet('oai', [], true);
                return new Response($xml, 'text/xml');
            }
        ]
    ]
]);
