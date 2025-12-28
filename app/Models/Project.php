<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('user_projects', function (Builder $builder) {
            if (auth()->check() && ! auth()->user()->isAdmin()) {
                $builder->whereHas('users', function ($query) {
                    $query->where('users.id', auth()->id());
                });
            }
        });
    }
}
