<?php

namespace App\Observers;

use App\Enums\TaskStatus;
use App\Events\Task\TaskCreated;
use App\Events\Task\TaskStatusChanged;
use App\Events\Task\TaskUpdated;
use App\Models\Task;

class TaskObserver
{
    public function created(Task $task): void
    {
        event(new TaskCreated($task));
    }

    public function updated(Task $task): void
    {
        $changes = $task->getChanges();

        event(new TaskUpdated($task, $changes));

        if (isset($changes['status'])) {
            $oldStatus = $task->getOriginal('status');
            $newStatus = $task->status;

            if ($oldStatus instanceof TaskStatus && $newStatus instanceof TaskStatus) {
                event(new TaskStatusChanged($task, $oldStatus, $newStatus));
            }
        }
    }

    public function deleting(Task $task): void
    {
    }

    public function deleted(Task $task): void
    {
    }
}
