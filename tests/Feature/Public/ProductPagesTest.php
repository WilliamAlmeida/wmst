<?php

use function Pest\Laravel\get;

it('shows the product index page', function () {
    get('/produtos')
        ->assertOk()
        ->assertSee('"component":"public\\/products\\/Index"', false);
});

it('shows the agenda-clinic product page', function () {
    get('/produtos/agenda-clinic')
        ->assertOk()
        ->assertSee('"component":"public\\/products\\/Show"', false)
        ->assertSee('agenda-clinic', false);
});

it('shows the ibox-delivery product page', function () {
    get('/produtos/ibox-delivery')
        ->assertOk()
        ->assertSee('"component":"public\\/products\\/Show"', false)
        ->assertSee('ibox-delivery', false);
});

it('shows the conecta product page', function () {
    get('/produtos/conecta')
        ->assertOk()
        ->assertSee('"component":"public\\/products\\/Show"', false)
        ->assertSee('conecta', false);
});

it('returns 404 for unknown product slug', function () {
    get('/produtos/não-existe')->assertNotFound();
});

it('shows product pages in localized routes', function () {
    get('/es/produtos/agenda-clinic')
        ->assertOk()
        ->assertSee('"locale":"es"', false);

    get('/en/produtos/conecta')
        ->assertOk()
        ->assertSee('"locale":"en"', false);
});
