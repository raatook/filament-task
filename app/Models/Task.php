<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== TaskStatus::DONE;
    }

    /**
     * Check if task is urgent
     */
    public function isUrgent(): bool
    {
        return $this->priority->isUrgent();
    }

    /**
     * Scope for filtering user tasks
     */
    protected static function booted()
    {
        static::addGlobalScope('user_tasks', function (Builder $builder) {
            if (auth()->check() && ! auth()->user()->isAdmin()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    /**
     * Scope for urgent tasks
     */
    public function scopeUrgent(Builder $query): Builder
    {
        return $query->whereIn('priority', [
            TaskPriority::URGENT,
            TaskPriority::CRITICAL
        ]);
    }

    /**
     * Scope for overdue tasks
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', TaskStatus::DONE);
    }

    /**
     * Scope for pending tasks
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::PENDING);
    }

    /**
     * Scope for in progress tasks
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::IN_PROGRESS);
    }

    /**
     * Scope for completed tasks
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', TaskStatus::DONE);
    }
}
