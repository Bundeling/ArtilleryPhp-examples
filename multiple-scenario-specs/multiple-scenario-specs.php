<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$scenarios = ['armadillo', 'dino', 'pony'];
$animal = $scenarios[1];

$artillery = Artillery::fromYaml(__DIR__ . '/common-config.yml')
	->merge(Artillery::fromYaml(__DIR__ . "/scenarios/$animal.yml"))
	->build();