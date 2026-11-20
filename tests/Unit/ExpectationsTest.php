<?php

use App\Enums\TaskPriority;

describe('the expectation API', function () {
    it('compares values', function () {
        expect(2 + 3)->toBe(5)
            ->and(2 + 3)->toEqual(5.0)
            ->and(5)->toBeGreaterThan(4)
            ->and(5)->toBeBetween(1, 10);
    });

    it('negates with not', function () {
        expect('Taskly')->not->toBeEmpty()
            ->and(['a', 'b'])->not->toContain('c');
    });

    it('chains many checks on one value', function () {
        expect('Write the analytical engine notes')
            ->toStartWith('Write')
            ->toEndWith('notes')
            ->toContain('analytical')
            ->toHaveLength(33);
    });

    it('walks a collection with each', function () {
        expect(TaskPriority::cases())->each->toBeInstanceOf(TaskPriority::class);
    });

    it('matches a collection in order with sequence', function () {
        expect(['low', 'medium', 'high'])->sequence(
            fn ($value) => $value->toBe('low'),
            fn ($value) => $value->toBe('medium'),
            fn ($value) => $value->toBe('high'),
        );
    });

    it('reaches into properties with higher-order expectations', function () {
        $task = (object) ['title' => 'Ship episode two', 'priority' => 'high'];

        expect($task)
            ->title->toBe('Ship episode two')
            ->priority->toBe('high');
    });

    it('cannot reach a property called value — Pest owns that name', function () {
        // expect(TaskPriority::High)->value->toBe('high') does NOT work:
        // Expectation has its own $value property holding the wrapped subject,
        // so ->value hands you the enum back rather than a new expectation.
        expect(TaskPriority::High->value)->toBe('high')
            ->and(TaskPriority::High)->name->toBe('High');
    });
});

describe('the format expectations Pest 5 added', function () {
    it('validates the formats we actually use', function () {
        expect('ada@taskly.test')->toBeEmail()
            ->and('https://taskly.test')->toBeUrl()
            ->and('taskly.test')->toBeDomain()
            ->and('taskly.test')->toBeHostname()
            ->and('192.168.1.1')->toBeIpAddress()
            ->and('3D:F2:C9:A6:B3:4F')->toBeMacAddress()
            ->and('01JQ8Z3M4N5P6Q7R8S9T0V1W2X')->toBeUlid()
            ->and('9d1c8b7a-6f5e-4d3c-8b2a-1f0e9d8c7b6a')->toBeUuid()
            ->and('dGFza2x5')->toBeBase64()
            ->and('1a2b3c')->toBeHexadecimal();
    });

    it('validates naming conventions', function () {
        expect('due_date')->toBeSnakeCase()
            ->and('dueDate')->toBeCamelCase()
            ->and('due-date')->toBeKebabCase()
            ->and('DueDate')->toBeStudlyCase();
    });
});
