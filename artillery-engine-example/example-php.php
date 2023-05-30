<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('system-under-test-endpoint')
	->set('example', ['mandatoryString' => 'a configuration setting for our engine'])
    ->addPhase(['arrivalRate' => 1, 'duration' => 1])
    ->setEngine('example');

$scenario = Artillery::scenario("A scenario using the custom 'example' engine")
    ->setEngine('example')
    ->addRequest(Artillery::anyRequest('doSomething', ['id' => 123]))
    ->addRequest(Artillery::anyRequest('doSomething', ['id' => 456]));

$artillery->addScenario($scenario)->build();