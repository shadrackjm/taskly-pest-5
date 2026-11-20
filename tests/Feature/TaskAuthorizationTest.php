<?php

use App\Models\Task;
use App\Models\User;

it('lets an owner view their task', function () {
    $user = login();
    $task = Task::factory()->for($user)->create();

    $this->get(route('tasks.show', $task))->assertOk();
});

it('forbids viewing someone else task', function () {
    login();
    $task = Task::factory()->for(User::factory())->create();

    $this->get(route('tasks.show', $task))->assertForbidden();
});

it('forbids deleting someone else task', function () {
    login();
    $task = Task::factory()->for(User::factory())->create();

    $this->delete(route('tasks.destroy', $task))->assertForbidden();

    $this->assertDatabaseHas('tasks', ['id' => $task->id]);
});

it('forbids completing someone else task', function () {
    login();
    $task = Task::factory()->for(User::factory())->create();

    $this->patch(route('tasks.complete', $task))->assertForbidden();

    expect($task->fresh()->isComplete())->toBeFalse();
});

it('lets an owner delete their task', function () {
    $user = login();
    $task = Task::factory()->for($user)->create();

    $this->delete(route('tasks.destroy', $task))->assertRedirect(route('tasks.index'));

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('404s on a task that does not exist', function () {
    login();

    $this->get('/tasks/99999')->assertNotFound();
});
