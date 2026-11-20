<?php

use App\Models\Task;
use App\Models\User;

it('lists the signed-in user pending tasks', function () {
    $user = login();

    Task::factory()->for($user)->create(['title' => 'Buy milk']);
    Task::factory()->for($user)->completed()->create(['title' => 'Already done']);

    $this->get('/tasks')
        ->assertOk()
        ->assertSee('Buy milk')
        ->assertDontSee('Already done');
});

it('never leaks another user tasks', function () {
    login();

    Task::factory()->for(User::factory())->create(['title' => 'Someone else task']);

    $this->get('/tasks')
        ->assertOk()
        ->assertDontSee('Someone else task');
});

it('tells a user with nothing to do', function () {
    login();

    $this->get('/tasks')->assertSee('Nothing to do');
});

it('orders tasks by due date', function () {
    $user = login();

    Task::factory()->for($user)->create(['title' => 'Later', 'due_date' => now()->addWeek()]);
    Task::factory()->for($user)->create(['title' => 'Sooner', 'due_date' => now()->addDay()]);

    $this->get('/tasks')->assertSeeInOrder(['Sooner', 'Later']);
});
