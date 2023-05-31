<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

// region Step 1: Create a new Artillery instance

//You can use Artillery::new($target) to get a new instance, and use the fluent interface to set config values:
$artillery = Artillery::new('http://localhost:3000')
	->addPhase(['duration' => 60, 'arrivalRate' => 5, 'rampTo' => 20], 'Warm up')
	->addPhase(['duration' => 60, 'arrivalRate' => 20], 'Sustain')
	->setPlugin('expect');

// You can also create one from a full or partial array representation:
$artillery = Artillery::fromArray([
	'config' => [
		'target' => 'http://localhost:3000',
		'phases' => [
			['duration' => 60, 'arrivalRate' => 5, 'rampTo' => 20, 'name' => 'Warm up'],
			['duration' => 60, 'arrivalRate' => 20, 'name' => 'Sustain'],
		],
		'plugins' => [
			// To produce an empty object as "{  }", use stdClass.
			// This is automatic when using setPlugin(s), setEngine(s) and setJson(s).
			'expect' => new stdClass(),
		]
	]
]);

// And from an existing YAML file, or other Artillery instance:
$file = __DIR__ . '/default-config.yml';
$default = Artillery::fromYaml($file);

$artillery = Artillery::from($default);

// endregion

// region Step 2: Define the flow of your scenario and add it to the Artillery instance:

// Create some requests:
$loginRequest = Artillery::request('get', '/login')
	->addCapture('token', 'json', '$.token')
	->addExpect('statusCode', 200)
	->addExpect('contentType', 'json')
	->addExpect('hasProperty', 'token');

$inboxRequest = Artillery::request('get', '/inbox')
	->setQueryString('token', '{{ token }}')
	->addExpect('statusCode', 200);

// Create a flow with the requests, and a 500ms delay between:
$flow = Artillery::scenario()
	->addRequest($loginRequest)
	->addThink(0.5)
	->addRequest($inboxRequest);

// Let's loop the flow 10 times:
$scenario = Artillery::scenario()->addLoop($flow, 10);

// Add the scenario to the Artillery instance:
$artillery->addScenario($scenario);

// endregion

// region Step 3: Export the YAML:

// Without argument will build the YAML as the same name as the php file:
$artillery->build();

// Maybe even run the script right away:
//$artillery->run();

// endregion