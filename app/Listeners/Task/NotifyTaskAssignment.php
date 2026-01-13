<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskCreated;
use Illuminate\Support\Facades\Log;

class NotifyTaskAssignment
{
    public function handle(TaskCreated $event): void
    {
        Log::info('Task assigned to user', [
            'task_id' => $event->task->id,
            'user_id' => $event->task->user_id,
            'user_name' => $event->task->user->name,
        ]);
    }
}
