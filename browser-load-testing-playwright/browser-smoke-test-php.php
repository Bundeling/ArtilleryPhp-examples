<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('https://arftillery.io')
	->addPayload('./pages.csv', ['url'])
	->setEngine('playwright')
	->setProcessor('./flows.js');

$scenario = Artillery::scenario('Check page')
	->setEngine('playwright')
	->set('flowFunction', 'checkPage');

$artillery->addScenario($scenario)->build();