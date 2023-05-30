<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

// In Artillery, each VU will be assigned to one of the defined
// scenarios. By default, each scenario has a weight of 1, meaning
// each scenario has the same probability of getting assigned to a
// VU.
//
// By specifying a weight in a scenario, you'll increase the chances
// of Artillery assigning the scenario for a VU. The probability of
// a scenario getting chosen depends on the total weight for all
// scenarios.
//
// To learn more, read the Artillery documentation on scenario weights:
// https://artillery.io/docs/guides/guides/test-script-reference.html#Scenario-weights
Artillery::new('http://localhost:3000')
    ->addPhase(['duration' => 600, 'arrivalRate' => 25])
    ->addScenario(Artillery::scenario('Access the /common route')
        // Approximately 60% of all VUs will access this scenario:
        ->setWeight(6)
        ->addRequest(Artillery::request('get', '/common')))
    ->addScenario(Artillery::scenario('Access the /average route')
        // Approximately 30% of all VUs will access this scenario:
        ->setWeight(3)
        ->addRequest(Artillery::request('get', '/average')))
    ->addScenario(Artillery::scenario('Access the /rare route')
        // Approximately 10% of all VUs will access this scenario:
        ->setWeight(1)
        ->addRequest(Artillery::request('get', '/rare')))
	->build();
