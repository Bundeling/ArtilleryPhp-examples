<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('https://artillery.io')
    ->addPhase(['arrivalRate' => 1, 'duration' => 10])
    ->setEngine('playwright')
    ->setProcessor('./flows.js');

$scenario = Artillery::scenario('A flow with multiple steps')
    ->setEngine('playwright')
    ->set('flowFunction', 'multistepWithCustomMetrics');

$artillery->addScenario($scenario)->build();