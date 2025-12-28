<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AllUsersActivityTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return __('All Users Activity Overview');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->where('role', 'user')
                    ->withCount([
                        'tasks',
                        'tasks as completed_tasks_count' => function ($query) {
                            $query->where('status', 'done');
                        },
                        'tasks as in_progress_tasks_count' => function ($query) {
                            $query->where('status', 'in_progress');
                        },
                        'tasks as pending_tasks_count' => function ($query) {
                            $query->where('status', 'pending');
                        },
                        'tasks as overdue_tasks_count' => function ($query) {
                            $query->where('status', '!=', 'done')
                                ->where('due_date', '<', now());
                        },
                    ])
            )
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user'),

                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('tasks_count')
                    ->label(__('Total Tasks'))
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('completed_tasks_count')
                    ->label(__('Completed'))
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->icon('heroicon-o-check-circle'),

                TextColumn::make('in_progress_tasks_count')
                    ->label(__('In Progress'))
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path'),

                TextColumn::make('pending_tasks_count')
                    ->label(__('Pending'))
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->icon('heroicon-o-clock'),

                TextColumn::make('overdue_tasks_count')
                    ->label(__('Overdue'))
                    ->badge()
                    ->color(fn ($record) => $record->overdue_tasks_count > 0 ? 'danger' : 'success')
                    ->sortable()
                    ->icon(fn ($record) => $record->overdue_tasks_count > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check'),

                TextColumn::make('completion_rate')
                    ->label(__('Completion Rate'))
                    ->getStateUsing(function ($record) {
                        if ($record->tasks_count == 0) {
                            return '0%';
                        }

                        return round(($record->completed_tasks_count / $record->tasks_count) * 100, 1).'%';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->tasks_count == 0) {
                            return 'gray';
                        }
                        $rate = ($record->completed_tasks_count / $record->tasks_count) * 100;

                        return $rate >= 70 ? 'success' : ($rate >= 40 ? 'warning' : 'danger');
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->orderByRaw(
                            "CASE WHEN tasks_count = 0 THEN 0 ELSE (completed_tasks_count / tasks_count * 100) END {$direction}"
                        );
                    }),
            ])
            ->defaultSort('tasks_count', 'desc')
            ->emptyStateHeading(__('No users found'))
            ->emptyStateDescription(__('There are no users with tasks yet.'))
            ->emptyStateIcon('heroicon-o-users');
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
