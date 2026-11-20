<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use Illuminate\Support\Facades\Log;

class LogTaskCompletion
{
    public function handle(TaskCompleted $event): void
    {
        Log::info('Task completed', [
            'task_id' => $event->task->id,
            'user_id' => $event->task->user_id,
        ]);
    }
}
