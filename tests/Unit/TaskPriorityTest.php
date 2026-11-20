<?php

use App\Enums\TaskPriority;

it('labels every priority', function (TaskPriority $priority, string $expected) {
    expect($priority->label())->toBe($expected);
})->with([
    [TaskPriority::Low, 'Low'],
    [TaskPriority::Medium, 'Medium'],
    [TaskPriority::High, 'High'],
]);

it('treats only high priority as urgent', function () {
    expect(TaskPriority::High->isUrgent())->toBeTrue()
        ->and(TaskPriority::Medium->isUrgent())->toBeFalse()
        ->and(TaskPriority::Low->isUrgent())->toBeFalse();
});

it('exposes exactly three priorities', function () {
    expect(TaskPriority::cases())->toHaveCount(3);
});
