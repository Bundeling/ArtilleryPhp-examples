<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

use ArtilleryPhp\Artillery;

Artillery::new()
    ->addScenario(
        Artillery::scenario('Pony')
            ->addRequest(Artillery::request('get', '/pony')->addExpect('statusCode', 200)))
    ->build();