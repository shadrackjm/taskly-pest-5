<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Every test closure is bound to a PHPUnit test case class. Feature and Browser
| tests get the full Laravel TestCase plus RefreshDatabase, so each one starts
| from an empty, migrated database. Unit tests stay deliberately plain — no
| framework, no database, no bootstrapping.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Browser');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| Custom expectations let you name a domain rule once and reuse it everywhere,
| so the failure message speaks your language instead of PHPUnit's.
|
*/

expect()->extend('toBeOverdue', function () {
    /** @var Task $task */
    $task = $this->value;

    expect($task->isOverdue())->toBeTrue();

    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Helpers exposed globally to every test, so common setup never gets copied
| from file to file.
|
*/

function login(?User $user = null): User
{
    $user ??= User::factory()->create();

    test()->actingAs($user);

    return $user;
}
