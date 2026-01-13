<?php

namespace App\Providers;

use App\Events\Task\TaskCreated;
use App\Events\Task\TaskStatusChanged;
use App\Events\Task\TaskUpdated;
use App\Listeners\Task\LogTaskCreation;
use App\Listeners\Task\NotifyTaskAssignment;
use App\Listeners\Task\UpdateProjectProgress;
use App\Models\Task;
use App\Observers\TaskObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskCreated::class => [
            LogTaskCreation::class,
            NotifyTaskAssignment::class,
        ],
        TaskStatusChanged::class => [
            UpdateProjectProgress::class,
        ],
        TaskUpdated::class => [
        ],
    ];


    public function boot(): void
    {
        Task::observe(TaskObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
