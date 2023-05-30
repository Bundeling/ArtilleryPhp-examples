<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$myPet = ['species' => 'pony', 'name' => 'Tiki'];

$artillery = Artillery::new('http://localhost:3000')
    ->setProcessor('./metrics.js')
    ->addPhase(['arrivalRate' => 25, 'duration' => 60])
    ->addScenario(
        Artillery::scenario()
            ->addRequest(Artillery::request('post', '/pets')->setJsons($myPet))
            ->addAfterResponse('trackPets'));

$artillery->build();