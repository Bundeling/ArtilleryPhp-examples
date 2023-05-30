<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('https://artillery.io')
    ->addPhase(['arrivalRate' => 1, 'duration' => 10])
    ->setPlugin('hello-world', ['greeting' => 'Hello world! 👋'])
    ->addScenario(Artillery::request('get', '/'))
    ->build();