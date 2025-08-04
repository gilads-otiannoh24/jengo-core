<?php

use Tests\FeatureTestCase;
use Tests\Support\Controllers\TestController;

uses(FeatureTestCase::class);

test("throws back a session errors tempdata with invalid request", function () {
    $routes = [
        ['POST', 'test', [TestController::class, 'index']],
    ];

    $this->withRoutes($routes)->call('post', 'test', [
        'name' => 'John',
        'email' => 'john@example.com',
        'password' => 'password',
    ]);

    expect(session("errors"))->toBeTruthy();
});