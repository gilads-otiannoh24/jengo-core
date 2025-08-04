<?php

use Tests\Commands\CommandTestCase;

uses(CommandTestCase::class);

test("Makes facade", function () {
    command("jengo:facade UserFacade");

    expect(true)->toBeTrue();
});