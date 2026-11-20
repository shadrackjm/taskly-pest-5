<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

it('accepts every valid priority', function (TaskPriority $priority) {
    login();

    $this->post('/tasks', ['title' => 'A valid title', 'priority' => $priority->value])
        ->assertSessionHasNoErrors();
})->with('priorities');

it('rejects a title that is too short', function (string $title) {
    login();

    $this->post('/tasks', ['title' => $title, 'priority' => 'low'])
        ->assertSessionHasErrors('title');
})->with('invalid titles');

it('scopes tasks to their owner', function (User $user) {
    Task::factory()->for($user)->create(['title' => 'Owned task']);

    expect($user->tasks()->count())->toBe(1);
})->with([
    'a freshly made user' => fn () => User::factory()->create(),
]);

it('combines two datasets', function (TaskPriority $priority, string $title) {
    login();

    $this->post('/tasks', ['title' => $title, 'priority' => $priority->value])
        ->assertSessionHasErrors('title');
})->with('urgent priorities')->with('invalid titles');
