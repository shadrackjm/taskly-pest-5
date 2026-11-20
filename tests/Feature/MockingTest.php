<?php

use App\Services\HolidayService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;

it('swaps the holiday service for a mock', function () {
    $this->mock(HolidayService::class, function (MockInterface $mock) {
        $mock->shouldReceive('isHoliday')->once()->andReturn(true);
    });

    login();

    $this->post('/tasks', [
        'title' => 'Mocked holiday check',
        'priority' => 'low',
        'due_date' => now()->addMonth()->toDateString(),
    ])->assertSessionHas('warning');
});

it('uses a spy to check the service was asked about the right date', function () {
    Http::fake();

    $spy = $this->spy(HolidayService::class);

    login();

    $this->post('/tasks', [
        'title' => 'Spied holiday check',
        'priority' => 'low',
        'due_date' => '2026-12-25',
    ]);

    $spy->shouldHaveReceived('isHoliday')
        ->withArgs(fn (Carbon $date) => $date->toDateString() === '2026-12-25');
});

it('never calls the holiday service when there is no due date', function () {
    $spy = $this->spy(HolidayService::class);

    login();

    $this->post('/tasks', ['title' => 'No due date', 'priority' => 'low']);

    $spy->shouldNotHaveReceived('isHoliday');
});
