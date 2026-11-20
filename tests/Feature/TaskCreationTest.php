<?php

use App\Enums\TaskPriority;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake(['api.taskly.test/*' => Http::response(['is_holiday' => false])]);
});

it('stores a task for the signed-in user', function () {
    $user = login();

    $response = $this->post('/tasks', [
        'title' => 'Write the tests first',
        'description' => 'Red, green, refactor.',
        'priority' => 'high',
        'due_date' => now()->addWeek()->toDateString(),
    ]);

    $response->assertRedirect(route('tasks.index'));
    $response->assertSessionHas('message', 'Task created.');

    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title' => 'Write the tests first',
        'priority' => 'high',
    ]);
});

it('defaults a new task to incomplete', function () {
    login();

    $this->post('/tasks', [
        'title' => 'Something to do',
        'priority' => 'low',
    ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Something to do',
        'completed_at' => null,
    ]);
});

it('casts the stored priority back to an enum', function () {
    $user = login();

    $this->post('/tasks', ['title' => 'Casting check', 'priority' => 'medium']);

    expect($user->tasks()->sole()->priority)->toBe(TaskPriority::Medium);
});

it('shows the create form with every priority', function () {
    login();

    $this->get('/tasks/create')
        ->assertOk()
        ->assertSee('Low')
        ->assertSee('Medium')
        ->assertSee('High');
});
