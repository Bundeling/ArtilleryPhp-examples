<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('https://www.artillery.io')
    ->addPayload('./request-response.csv', ['url', 'code'], ['loadAll' => true, 'name' => 'data'])
    ->setPlugin('expect');

$loopScenario = Artillery::scenario()
    ->addLoop(Artillery::request('get', '{{ $loopElement.url }}')
        ->setFollowRedirect(false)
        ->addExpect('statusCode', '{{ $loopElement.code }}'),
        over: 'data');

$artillery->addScenario($loopScenario)->build();