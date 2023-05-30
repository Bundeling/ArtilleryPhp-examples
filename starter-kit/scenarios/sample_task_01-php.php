<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('https://run.mocky.io')
	->addPhase(['duration' => 30, 'arrivalRate' => 3, 'maxVusers' => 10])
	->setProcessor('../processors/sample_task_01.js')
	->addScenario(
		Artillery::scenario()
			->addFunction('generateRandomTiming')
			->addRequest(Artillery::request('get', '/v3/0eff1291-866e-4afd-a462-e3711607caa4?mocky-delay={{ timing }}ms')
				->addAfterResponse('printStatus')));

$artillery->build();