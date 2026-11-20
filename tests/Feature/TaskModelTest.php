<?php

use App\Models\Task;

it('is not complete when completed_at is null', function () {
    expect((new Task)->isComplete())->toBeFalse();
});

it('is complete once completed_at is set', function () {
    $task = new Task;
    $task->completed_at = now();

    expect($task->isComplete())->toBeTrue();
});

it('is not overdue without a due date', function () {
    expect((new Task)->isOverdue())->toBeFalse();
});

it('is overdue when the due date has passed', function () {
    $task = new Task(['due_date' => now()->subDay()]);

    expect($task->isOverdue())->toBeTrue();
});

it('is not overdue when the due date is ahead', function () {
    $task = new Task(['due_date' => now()->addDay()]);

    expect($task->isOverdue())->toBeFalse();
});

it('is never overdue once complete', function () {
    $task = new Task(['due_date' => now()->subWeek()]);
    $task->completed_at = now();

    expect($task->isOverdue())->toBeFalse();
});

it('allows exactly these fields to be mass assigned', function () {
    expect((new Task)->getFillable())
        ->toBe(['title', 'description', 'priority', 'due_date']);
});

it('never lets completion be mass assigned', function () {
    $task = Task::factory()->create();

    $task->fill(['completed_at' => now(), 'user_id' => 999]);

    expect($task->isComplete())->toBeFalse()
        ->and($task->user_id)->not->toBe(999);
});
