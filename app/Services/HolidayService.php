<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class HolidayService
{
    public function isHoliday(Carbon $date): bool
    {
        $response = Http::get('https://api.taskly.test/holidays', [
            'date' => $date->toDateString(),
        ]);

        if ($response->failed()) {
            return false;
        }

        return (bool) $response->json('is_holiday', false);
    }
}
