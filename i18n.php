<?php

use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;

$translations = [];
$root = __DIR__ . '/i18n';
$english = F::read($root . '/en.json');

foreach (Dir::files($root) as $file) {
    $locale = basename($file, '.json');
    if ($content = F::read($root . '/' . $file)) {
        $translations[$locale] = json_decode($content, true);
    }
    else {
        $translations[$locale] = json_decode($english, true);
    }
}

return $translations;