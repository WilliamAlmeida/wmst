<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

test('public home is available in default and prefixed locales', function () {
    get('/')
        ->assertOk()
        ->assertSee('"component":"public\\/Home"', false)
        ->assertSee('"locale":"pt_BR"', false);

    get('/es')
        ->assertOk()
        ->assertSee('"component":"public\\/Home"', false)
        ->assertSee('"locale":"es"', false);

    get('/en')
        ->assertOk()
        ->assertSee('"component":"public\\/Home"', false)
        ->assertSee('"locale":"en"', false);
});
