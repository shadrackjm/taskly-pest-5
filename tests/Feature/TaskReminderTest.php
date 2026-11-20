<?php

use App\Jobs\SendTaskReminder;
use App\Mail\TaskDueSoonMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Http::fake();
});

it('queues a reminder for a high-priority task', function () {
    Queue::fake();

    login();

    $this->post('/tasks', ['title' => 'Urgent thing', 'priority' => 'high']);

    Queue::assertPushed(SendTaskReminder::class, fn (SendTaskReminder $job) => $job->task->title === 'Urgent thing');
});

it('queues nothing for a low-priority task', function () {
    Queue::fake();

    login();

    $this->post('/tasks', ['title' => 'Whenever', 'priority' => 'low']);

    Queue::assertNothingPushed();
});

it('mails the task owner when the reminder job runs', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'ada@taskly.test']);
    $task = Task::factory()->for($user)->highPriority()->create();

    (new SendTaskReminder($task))->handle();

    Mail::assertSent(TaskDueSoonMail::class, fn (TaskDueSoonMail $mail) => $mail->hasTo('ada@taskly.test')
        && $mail->task->is($task));
});

it('sends exactly one reminder email', function () {
    Mail::fake();

    $task = Task::factory()->highPriority()->create();

    (new SendTaskReminder($task))->handle();

    Mail::assertSentCount(1);
});
