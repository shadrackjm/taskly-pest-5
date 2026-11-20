<?php

use App\Models\User;

it('shows the login page to guests', function () {
    $this->get('/login')->assertOk()->assertSee('Sign in');
});

it('signs a user in with correct credentials', function () {
    $user = User::factory()->create(['email' => 'ada@taskly.test']);

    $response = $this->post('/login', [
        'email' => 'ada@taskly.test',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('tasks.index'));
    $this->assertAuthenticatedAs($user);
});

it('rejects a wrong password', function () {
    User::factory()->create(['email' => 'ada@taskly.test']);

    $response = $this->post('/login', [
        'email' => 'ada@taskly.test',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('signs a user out', function () {
    login();

    $this->post('/logout')->assertRedirect('/');

    $this->assertGuest();
});

it('redirects guests away from the task list', function () {
    $this->get('/tasks')->assertRedirect('/login');
});

it('allows exactly these user fields to be mass assigned', function () {
    expect((new User)->getFillable())->toBe(['name', 'email', 'password']);
});

it('hides the password and remember token from arrays', function () {
    expect((new User)->getHidden())->toBe(['password', 'remember_token'])
        ->and(User::factory()->create()->toArray())->not->toHaveKey('password')->not->toHaveKey('remember_token');
});
