<?php

use App\Models\Task;
use App\Models\User;

it('shows the sign-in page', function () {
    $page = visit('/login');

    $page->assertTitle('Sign in')
        ->assertSee('Welcome back')
        ->assertVisible('#email')
        ->assertNoJavascriptErrors()
        ->assertNoConsoleLogs()
        ->assertNoAccessibilityIssues();
});

it('signs a user in and shows their tasks', function () {
    $user = User::factory()->create(['email' => 'ada@taskly.test']);
    Task::factory()->for($user)->create(['title' => 'Record episode twelve']);

    $page = visit('/login');

    $page->fill('email', 'ada@taskly.test')
        ->fill('password', 'password')
        ->click('Sign in')
        ->assertPathIs('/tasks')
        ->assertSee('Record episode twelve');
});

it('creates a task through the browser', function () {
    $user = User::factory()->create(['email' => 'ada@taskly.test']);

    $page = visit('/login');

    $page->fill('email', 'ada@taskly.test')
        ->fill('password', 'password')
        ->click('Sign in')
        ->click('New task')
        ->assertPathIs('/tasks/create')
        ->fill('title', 'Bought on camera')
        ->select('priority', 'high')
        ->click('Create task')
        ->assertPathIs('/tasks')
        ->assertSee('Task created.')
        ->assertSee('Bought on camera');

    expect($user->tasks()->sole()->title)->toBe('Bought on camera');
});

it('shows validation errors in the browser', function () {
    $user = User::factory()->create(['email' => 'ada@taskly.test']);

    $page = visit('/login');

    $page->fill('email', 'ada@taskly.test')
        ->fill('password', 'password')
        ->click('Sign in')
        ->click('New task')
        ->fill('title', 'ab')
        ->click('Create task')
        ->assertSee('A task title needs at least 3 characters.');
});

it('works on a phone', function () {
    User::factory()->create(['email' => 'ada@taskly.test']);

    $page = visit('/login')->on()->mobile();

    $page->assertSee('Sign in')->assertNoSmoke();
});

it('filters tasks from the browser', function () {
    $user = User::factory()->create(['email' => 'ada@taskly.test']);
    Task::factory()->for($user)->create(['title' => 'Buy milk', 'priority' => 'low']);
    Task::factory()->for($user)->create(['title' => 'Renew the domain', 'priority' => 'high']);

    $page = visit('/login');

    $page->fill('email', 'ada@taskly.test')
        ->fill('password', 'password')
        ->click('Sign in')
        ->assertPathIs('/tasks')
        ->assertSee('Buy milk')
        ->assertSee('Renew the domain')
        ->fill('search', 'Buy')
        ->click('Filter')
        ->assertSee('Buy milk')
        ->assertDontSee('Renew the domain');
});
