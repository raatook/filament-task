<?php

namespace App\Events\Task;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Task $task,
        public readonly TaskStatus $oldStatus,
        public readonly TaskStatus $newStatus
    ) {}
}
