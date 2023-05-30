<?php declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$productionEnvironment = Artillery::new('http://wontresolve.prod:44321')
	->addPhase(['duration' => 10, 'arrivalRate' => 10]);

$localEnvironment = Artillery::new('http://127.0.0.1:3003')
	->addPhase(['duration' => 60, 'arrivalRate' => 20]);

$artillery = Artillery::new('https://api.someservice.com')
    ->addPhase(['duration' => 5, 'arrivalRate' => 1], 'Warming up the application')
    ->addPhase(['duration' => 10, 'rampTo' => 30], 'Mild load on the application')
    ->addPhase(['duration' => 180, 'arrivalRate' => 3, 'maxVusers' => 120], 'Putting load on the application')
    ->addPayload('../data/users.csv', ['firstName', 'lastName', 'emailAddress'], ['order' => 'sequence', 'skipHeader' => true])
    ->addEnsureThreshold('http.response_time.p95', 200)
    ->addEnsureCondition('((vusers.created - vusers.completed)/vusers.created * 100) <= 1')
    ->setTls(false)
	->setHttpTimeout(15)
	->setHttpMaxSockets(6)
    ->setEnvironments([
		'production' => $productionEnvironment,
		'local' => $localEnvironment])
	->setVariables([
		'postcode' => ['SE1', 'EC1', 'E8', 'WH9'],
		'id' => ['8731', '9965', '2806']])
	->setProcessor('../processors/_baseProcessor.js');

$defaultHeaders = [
	'x-api-key' => '{{ $processEnvironment.SERVICE_API_KEY }}',
	'Content-Type' => 'application/json',
	'Accept' => 'application/json'];

$firstScenario = Artillery::scenario('The first flow')
	->addFunction('generateRandomData')
	->addRequest(
		Artillery::request('get', '/members/{{ id }}')
		    ->addAfterResponse('printStatus')
			->setHeaders($defaultHeaders))
	->addRequest(
		Artillery::request('post', '/members/member')
			->addAfterResponse('printStatus')
			->setJsons([
				'id' => '{{ id }}',
				'name' => '{{ name }}',
				'description' => 'Some randomly generated user',
				'salary' => 666000])
			->setHeaders($defaultHeaders));

$secondScenario = Artillery::scenario('The second flow')
	->addFunction('generateRandomData')
	->addRequest(
		Artillery::request('get', '/members/{{ id }}')
			->addAfterResponse('printStatus')
			->setHeaders($defaultHeaders))
	->addRequest(
		Artillery::request('post', '/members/member')
			->addAfterResponse('printStatus')
			->setJsons([
				'id' => '{{ id }}',
				'name' => '{{ name }}',
				'description' => 'Some randomly generated user',
				'salary' => 666000])
			->setHeaders($defaultHeaders));

$artillery->addScenarios([$firstScenario, $secondScenario])->build();
