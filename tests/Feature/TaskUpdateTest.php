<?php

use App\Enums\TaskPriority;
use App\Models\Task;
use App\Models\User;

it('updates a task the user owns', function () {
    $user = login();
    $task = Task::factory()->for($user)->create(['title' => 'Old title']);

    $this->patch(route('tasks.update', $task), [
        'title' => 'New title',
        'priority' => 'high',
    ])->assertRedirect(route('tasks.show', $task));

    expect($task->fresh())
        ->title->toBe('New title')
        ->priority->toBe(TaskPriority::High);
});

it('lets a partial update leave other fields alone', function () {
    $user = login();
    $task = Task::factory()->for($user)->create([
        'title' => 'Keep me',
        'priority' => 'low',
    ]);

    $this->patch(route('tasks.update', $task), ['priority' => 'high']);

    expect($task->fresh())
        ->title->toBe('Keep me')
        ->priority->toBe(TaskPriority::High);
});

it('rejects an invalid update', function () {
    $user = login();
    $task = Task::factory()->for($user)->create(['title' => 'Untouched']);

    $this->patch(route('tasks.update', $task), ['title' => 'ab'])
        ->assertSessionHasErrors('title');

    expect($task->fresh()->title)->toBe('Untouched');
});

it('forbids updating someone else task', function () {
    login();
    $task = Task::factory()->for(User::factory())->create(['title' => 'Not yours']);

    $this->patch(route('tasks.update', $task), ['title' => 'Hijacked'])
        ->assertForbidden();

    expect($task->fresh()->title)->toBe('Not yours');
});
