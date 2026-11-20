<?php

use App\Models\Task;

it('becomes overdue as time passes', function () {
    $this->travelTo('2026-06-01 09:00:00');

    $task = Task::factory()->create(['due_date' => '2026-06-10']);

    expect($task->isOverdue())->toBeFalse();

    $this->travelTo('2026-06-11 09:00:00');

    expect($task->fresh()->isOverdue())->toBeTrue();
});

it('finds overdue tasks with the scope', function () {
    $this->travelTo('2026-06-15 09:00:00');

    Task::factory()->create(['due_date' => '2026-06-01']);
    Task::factory()->create(['due_date' => '2026-06-30']);
    Task::factory()->completed()->create(['due_date' => '2026-06-01']);

    expect(Task::overdue()->count())->toBe(1);
});

it('stamps completion at the frozen time', function () {
    $this->freezeTime();

    $task = Task::factory()->create();
    $task->complete();

    expect($task->completed_at->toDateTimeString())->toBe(now()->toDateTimeString());
});
