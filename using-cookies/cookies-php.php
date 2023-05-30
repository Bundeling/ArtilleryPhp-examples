<?php declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use ArtilleryPhp\Artillery;

$emails = ['testuser1@artillery.io', 'testuser2@artillery.io',  'testuser3@artillery.io', 'testuser4@artillery.io', 'testuser5@artillery.io'];

$artillery = Artillery::new('http://localhost:3000')
    ->addPhase(['duration' => 600, 'arrivalRate' => 25])
    ->setVariable('email', $emails);

// In this scenario, the request to /login will capture the user's
// email and set it in a cookie, which will get returned in the response.
// The subsequent request to /account will return the value of the
// email from the saved cookie.
$serverCookieScenario = Artillery::scenario('Login and verify cookie')
    ->addRequest(Artillery::request('post', '/login')
        ->setJsons(['email' => '{{ email }}', 'password' => 'test-password-123']))
    ->addRequest(Artillery::request('get', '/account')
        ->addMatch('json', '$.user.email', '{{ email }}'));

// In this scenario, we'll manually set cookie values when making a
// request to /set-state, and validating the value saved in the cookie
// in a request to /state.
$manualCookieScenario = Artillery::scenario('Set cookie values')
    ->addRequest(Artillery::request('post', '/login')
        ->setJson('email', '{{ email }}')
        ->setJson('password', 'test-password-123'))
    ->addRequest(Artillery::request('post', '/set-state')
        ->setCookie('state', 'online'))
    ->addRequest(Artillery::request('get', '/state')
        ->addMatch('json', '$.currentState', 'online'))
    ->addRequest(Artillery::request('post', '/set-state')
        ->setCookie('state', 'busy'))
    ->addRequest(Artillery::request('get', '/state')
        ->addMatch('json', '$.currentState', 'busy'));

$artillery->addScenarios([$serverCookieScenario, $manualCookieScenario])->build();