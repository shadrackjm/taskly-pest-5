<?php

namespace App\Jobs;

use App\Mail\TaskDueSoonMail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendTaskReminder implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function handle(): void
    {
        Mail::to($this->task->user->email)->send(new TaskDueSoonMail($this->task));
    }
}
