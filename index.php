<?php

Kirby::plugin('pixelopen/kirby-googlemybusiness', [
    'blueprints' => [
        'tabs/googlemybusiness' => __DIR__ . '/blueprints/tabs/googlemybusiness.yml',
    ],
    'commands' => [
        'gmb:sync' => require __DIR__ . '/commands/sync.php',
        'gmb:reviews' => require __DIR__ . '/commands/reviews.php',
        'gmb:init' => require __DIR__ . '/commands/init.php',
        'gmb:sort' => require __DIR__ . '/commands/sort.php',
    ],
    'collections' => [
        'reviews' => require __DIR__ . '/collections/reviews.php',
    ],
    'snippets' => [
        'reviews' => __DIR__ . '/snippets/reviews.php',
        'business_info' => __DIR__ . '/snippets/business_info.php',
    ],
    'translations' => require __DIR__ . '/i18n.php',
]);
