<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('https://api.somewebsite.com')
	->addPhase(['duration' => 30, 'arrivalRate' => 3, 'maxVusers' => 10])
	->setProcessor('../processors/sample_task_02.js');

$scenario = Artillery::scenario()
	->addFunction('generateRandomData')
	->addRequest(Artillery::request('get', '/members/list')
	    ->addAfterResponse('printStatus'))
	->addRequest(Artillery::request('post', '/members/create')
	    ->addAfterResponse('printStatus')
	    ->setJsons(['id' => '{{ id }}', 'name' => '{{ name }}']))
	->addRequest(Artillery::request('get', '/members/{{ id }}')
	    ->addAfterResponse('printStatus'));

$artillery->addScenario($scenario)->build();