<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

Artillery::new('https://artillery.io')
	// Informal short run:
    ->setEnvironment('smoke', ['phases' => [['arrivalRate' => 1, 'duration' => 10]]])
	// Long-running job:
    ->setEnvironment('preprod', ['phases' => [['arrivalRate' => 5, 'duration' => 20]]])
    ->setEnvironment('dynamic', [
        'phases' => [[
            'arrivalRate' => '{{ $processEnvironment.ARRIVAL_RATE }}',
            'duration' => '{{ $processEnvironment.DURATION }}']]])
    ->addScenario(Artillery::scenario()->addRequest(Artillery::request('get', '/')))
    ->build();
