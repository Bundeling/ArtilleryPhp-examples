<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

use ArtilleryPhp\Artillery;

Artillery::new()
    ->addScenario(
        Artillery::scenario('Armadillo')
            ->addRequest(Artillery::request('get', '/armadillo')->addExpect('statusCode', 200)))
    ->build();