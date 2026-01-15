<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskStatusChanged;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Cache;

class UpdateProjectProgress
{
    public function handle(TaskStatusChanged $event): void
    {
        Cache::forget("project_stats_{$event->task->project_id}");

        if ($event->newStatus === TaskStatus::DONE) {
            $project = $event->task->project;

            $project->tasks()
                ->where('status', '!=', TaskStatus::DONE)
                ->exists();
            //
        }
    }
}
