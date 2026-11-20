<?php

use App\Models\Task;
use App\Models\User;

/**
 * Strip the parts of a rendered page that legitimately change between runs and
 * between machines. Without this, a snapshot fails the second time you run it —
 * the CSRF token is different — and fails again on a colleague's machine, whose
 * APP_URL is not yours. A snapshot is only as useful as it is deterministic.
 */
function stableHtml(string $html): string
{
    return str_replace(config('app.url'), 'http://taskly.test', preg_replace(
        '/name="_token" value="[^"]+"/',
        'name="_token" value="[CSRF]"',
        $html
    ));
}

it('renders the empty task list the same way every time', function () {
    login();

    expect(stableHtml($this->get('/tasks')->getContent()))->toMatchSnapshot();
});

it('renders a task card the same way every time', function () {
    $user = User::factory()->create(['name' => 'Ada Lovelace']);
    login($user);

    Task::factory()->for($user)->create([
        'title' => 'Write the analytical engine notes',
        'priority' => 'high',
        'due_date' => '2026-12-01',
    ]);

    expect(stableHtml($this->get('/tasks')->getContent()))->toMatchSnapshot();
});

it('keeps the validation error payload stable', function () {
    login();

    $errors = $this->post('/tasks', ['title' => 'ab'])
        ->assertSessionHasErrors()
        ->getSession()
        ->get('errors')
        ->getBag('default')
        ->toArray();

    expect($errors)->toMatchSnapshot();
});
