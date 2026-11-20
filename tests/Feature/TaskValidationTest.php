<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
    login();
});

it('rejects an invalid submission', function (array $payload, string $field) {
    $this->post('/tasks', $payload)->assertSessionHasErrors($field);

    $this->assertDatabaseCount('tasks', 0);
})->with([
    'no title' => [['priority' => 'low'], 'title'],
    'title too short' => [['title' => 'ab', 'priority' => 'low'], 'title'],
    'no priority' => [['title' => 'A valid title'], 'priority'],
    'unknown priority' => [['title' => 'A valid title', 'priority' => 'urgent'], 'priority'],
    'due date in the past' => [['title' => 'A valid title', 'priority' => 'low', 'due_date' => '2020-01-01'], 'due_date'],
    'due date not a date' => [['title' => 'A valid title', 'priority' => 'low', 'due_date' => 'next tuesday-ish'], 'due_date'],
]);

it('uses our own wording for a short title', function () {
    $this->post('/tasks', ['title' => 'ab', 'priority' => 'low'])
        ->assertSessionHasErrors(['title' => 'A task title needs at least 3 characters.']);
});

it('uses our own wording for a past due date', function () {
    $this->post('/tasks', ['title' => 'A valid title', 'priority' => 'low', 'due_date' => '2020-01-01'])
        ->assertSessionHasErrors(['due_date' => 'You cannot schedule a task in the past.']);
});

it('keeps the old input after a failed submission', function () {
    $this->post('/tasks', ['title' => 'ab', 'priority' => 'high'])
        ->assertSessionHasInput('priority', 'high');
});

it('accepts a task with no description and no due date', function () {
    $this->post('/tasks', ['title' => 'Bare minimum', 'priority' => 'low'])
        ->assertSessionHasNoErrors();

    $this->assertDatabaseCount('tasks', 1);
});
