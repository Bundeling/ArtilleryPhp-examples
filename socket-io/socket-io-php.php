<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('http://localhost:8080')
    ->addPhase(['duration' => 60, 'arrivalRate' => 25]);

$emitAndValidateResponse = Artillery::scenario('Emit and validate response')
    ->setEngine('socketio')
    ->addRequest(
        Artillery::wsRequest('emit')
            ->set('channel', 'echo')
            ->set('data', 'Hello from Artillery')
            ->set('response', ['channel' => 'echoResponse', 'data' => 'Hello from Artillery']));

$emitAndValidateAcknowledgement = Artillery::scenario('Emit and validate acknowledgement')
    ->setEngine('socketio')
    ->addRequest(
        Artillery::wsRequest('emit')
            ->set('channel', 'userDetails')
            ->set('acknowledge', [ 'match' => ['json' => '$.0.name', 'value' => 'Artillery']]));

$artillery->addScenarios([$emitAndValidateResponse, $emitAndValidateAcknowledgement])->build();