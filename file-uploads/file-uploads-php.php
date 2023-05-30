<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$artillery = Artillery::new('http://localhost:3000')
    ->addPhase(['duration' => 600, 'arrivalRate' => 25])
    // Enables the file upload plugin from Artillery Pro.
    ->setPlugin('http-file-uploads')
    // To randomize the files to upload during the test scenario,
    // set up variables with the names of the files to use. These
    // files are placed in the `/files` directory.
    ->setVariable('filename', ['artillery-logo.jpg', 'artillery-installation.pdf', 'sre-fundamental-rules.png']);

// The HTTP server has an endpoint (POST /upload) that accepts files
// through the `document` field.
$request = Artillery::request('post', '/upload')
    // The `fromFile` attribute tells Artillery to upload the
    // specified file. If the file cannot be read, this scenario
    // will report an ENOENT error.
    ->setFormData('document', ['fromFile' => './files/{{ filename }}']);

$artillery->addScenario($request)->build();