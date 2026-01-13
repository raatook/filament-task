<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskCreated;
use Illuminate\Support\Facades\Log;

class LogTaskCreation
{
    public function handle(TaskCreated $event): void
    {
        Log::info('Task created', [
            'task_id' => $event->task->id,
            'title' => $event->task->title,
            'user_id' => $event->task->user_id,
            'project_id' => $event->task->project_id,
        ]);
    }
}
