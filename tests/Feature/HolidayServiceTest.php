<?php

use App\Services\HolidayService;
use Illuminate\Support\Facades\Http;

it('reports a holiday when the API says so', function () {
    Http::fake([
        'api.taskly.test/*' => Http::response(['is_holiday' => true]),
    ]);

    expect(app(HolidayService::class)->isHoliday(now()))->toBeTrue();
});

it('reports no holiday when the API says so', function () {
    Http::fake([
        'api.taskly.test/*' => Http::response(['is_holiday' => false]),
    ]);

    expect(app(HolidayService::class)->isHoliday(now()))->toBeFalse();
});

it('fails safe when the holiday API is down', function () {
    Http::fake([
        'api.taskly.test/*' => Http::response(status: 500),
    ]);

    expect(app(HolidayService::class)->isHoliday(now()))->toBeFalse();
});

it('asks the API about the right date', function () {
    Http::fake();

    app(HolidayService::class)->isHoliday(now()->parse('2026-12-25'));

    Http::assertSent(fn ($request) => str_contains($request->url(), 'date=2026-12-25'));
});

it('warns the user when a due date lands on a holiday', function () {
    Http::fake([
        'api.taskly.test/*' => Http::response(['is_holiday' => true]),
    ]);

    login();

    $this->post('/tasks', [
        'title' => 'Christmas task',
        'priority' => 'low',
        'due_date' => now()->addMonths(2)->toDateString(),
    ])->assertSessionHas('warning');
});
