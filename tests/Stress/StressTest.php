<?php

use function Pest\Stressless\stress;

/**
 * Stress tests hit a REAL, RUNNING site — they are not part of the normal suite,
 * and they take seconds, not milliseconds. Run them on purpose:
 *
 *   ./vendor/bin/pest tests/Stress
 */
it('serves the login page under load', function () {
    $result = stress(config('app.url').'/login')
        ->for(3)->seconds()
        ->concurrently(requests: 5);

    expect($result->requests()->failed()->count())->toBe(0)
        ->and($result->requests()->duration()->med())->toBeLessThan(500);
});
