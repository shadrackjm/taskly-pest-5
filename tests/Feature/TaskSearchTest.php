<?php

use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $this->user = login();

    Task::factory()->for($this->user)->create(['title' => 'Buy milk', 'priority' => 'low']);
    Task::factory()->for($this->user)->create(['title' => 'Buy a birthday card', 'priority' => 'high']);
    Task::factory()->for($this->user)->create(['title' => 'Renew the domain', 'priority' => 'high']);
});

it('shows every pending task when no filters are given', function () {
    $this->get('/tasks')
        ->assertSee('Buy milk')
        ->assertSee('Buy a birthday card')
        ->assertSee('Renew the domain');
});

it('filters by a search term', function () {
    $this->get('/tasks?search=Buy')
        ->assertSee('Buy milk')
        ->assertSee('Buy a birthday card')
        ->assertDontSee('Renew the domain');
});

it('searches case-insensitively', function () {
    $this->get('/tasks?search=buy')->assertSee('Buy milk');
});

it('filters by priority', function () {
    $this->get('/tasks?priority=high')
        ->assertSee('Buy a birthday card')
        ->assertSee('Renew the domain')
        ->assertDontSee('Buy milk');
});

it('combines a search term and a priority', function () {
    $this->get('/tasks?search=Buy&priority=high')
        ->assertSee('Buy a birthday card')
        ->assertDontSee('Buy milk')
        ->assertDontSee('Renew the domain');
});

it('tells the user when a search matches nothing', function () {
    $this->get('/tasks?search=aardvark')
        ->assertSee('No tasks match')
        ->assertDontSee('Buy milk');
});

it('never searches outside the signed-in user tasks', function () {
    Task::factory()->for(User::factory())->create(['title' => 'Buy someone else milk']);

    $this->get('/tasks?search=Buy')
        ->assertSee('Buy milk')
        ->assertDontSee('Buy someone else milk');
});

it('ignores an unknown priority rather than erroring', function () {
    $this->get('/tasks?priority=urgent')
        ->assertOk()
        ->assertSee('Buy milk');
});

it('keeps completed tasks out of the results', function () {
    Task::factory()->for($this->user)->completed()->create(['title' => 'Buy stamps']);

    $this->get('/tasks?search=Buy')->assertDontSee('Buy stamps');
});
