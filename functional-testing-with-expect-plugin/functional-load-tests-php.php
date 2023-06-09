<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$loadEnvironment = Artillery::new()->addPhase(['duration' => 600, 'arrivalRate' => 25]);

// We don't need to specify a phase here. Artillery will
// launch a single VU when there is no phase definition:
$functionalEnvironment = Artillery::new()->setPlugin('expect');

$artillery = Artillery::new('http://localhost:3000')
	->setEnvironment('load', $loadEnvironment)
	->setEnvironment('functional', $functionalEnvironment);

$postUserRequest = Artillery::request('post', '/users')
	->setJson('username', 'new-user')
	->addCapture('id', 'json', '$.id')
	->addExpect('statusCode', 201)
	->addExpect('contentType', 'json')
	->addExpect('hasProperty', 'username')
	->addExpect('equals', ['new-user']);

$getUserRequest = Artillery::request('get', '/users/{{ id }}')
	->addExpect('statusCode', 200)
	->addExpect('contentType', 'json')
	->addExpect('hasProperty', 'username')
	->addExpect('equals', ['new-user']);

$deleteUserRequest = Artillery::request('delete', '/users/{{ id }}')
	->addExpect('statusCode', 204);

$scenario = Artillery::scenario()
	->addRequest($postUserRequest)
	->addRequest($getUserRequest)
	->addRequest($deleteUserRequest);

$artillery->addScenario($scenario)->build();
