<?php

use App\Events\TaskCompleted;
use App\Models\Task;
use Illuminate\Support\Facades\Event;

it('marks a task complete and stamps the time', function () {
    $this->freezeTime();

    $user = login();
    $task = Task::factory()->for($user)->create();

    $this->patch(route('tasks.complete', $task))->assertRedirect(route('tasks.index'));

    expect($task->fresh()->completed_at->toDateTimeString())->toBe(now()->toDateTimeString());
});

it('fires TaskCompleted when a task is completed', function () {
    Event::fake();

    $user = login();
    $task = Task::factory()->for($user)->create();

    $this->patch(route('tasks.complete', $task));

    Event::assertDispatched(TaskCompleted::class, fn (TaskCompleted $event) => $event->task->is($task));
});

it('does not fire TaskCompleted for a forbidden task', function () {
    Event::fake();

    login();
    $task = Task::factory()->create();

    $this->patch(route('tasks.complete', $task))->assertForbidden();

    Event::assertNotDispatched(TaskCompleted::class);
});

it('drops a completed task off the list', function () {
    $user = login();
    $task = Task::factory()->for($user)->create(['title' => 'Ship the series']);

    $this->get('/tasks')->assertSee('Ship the series');

    $this->patch(route('tasks.complete', $task));

    $this->get('/tasks')->assertDontSee('Ship the series');
});
