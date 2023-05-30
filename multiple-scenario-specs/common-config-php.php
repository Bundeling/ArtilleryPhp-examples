<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

Artillery::new('http://asciizoo.artillery.io:8080')
    ->setPlugins(['metrics-by-endpoint', 'expect'])
    ->build();