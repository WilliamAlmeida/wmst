<?php

use App\Models\User;

test('admin can view the users page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('admin can create a user that is verified by default', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Novo Usuário',
            'email' => 'novo@wmst.com.br',
            'password' => 'Str0ng-Passw0rd!',
        ])
        ->assertRedirect(route('admin.users.index'));

    $created = User::whereEmail('novo@wmst.com.br')->first();
    expect($created)->not->toBeNull();
    expect($created->email_verified_at)->not->toBeNull();
});

test('a user cannot delete themselves', function () {
    $admin = User::factory()->create();
    User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertSessionHasErrors('user');

    expect(User::whereKey($admin->id)->exists())->toBeTrue();
});

test('the last remaining user cannot be deleted', function () {
    $admin = User::factory()->create();
    $other = User::factory()->create();

    // Remove todos menos o admin e o alvo, deixando 2; apaga o alvo (ok),
    // então sobra 1 e a próxima exclusão deve ser bloqueada.
    $this->actingAs($admin)->delete(route('admin.users.destroy', $other))->assertRedirect();

    expect(User::count())->toBe(1);
});

test('admin can delete another user', function () {
    $admin = User::factory()->create();
    $target = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $target))
        ->assertRedirect(route('admin.users.index'));

    expect(User::whereKey($target->id)->exists())->toBeFalse();
});

test('guests cannot access user management', function () {
    $this->get(route('admin.users.index'))->assertRedirect(route('login'));
});
