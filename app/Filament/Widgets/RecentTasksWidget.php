<?php

namespace App\Filament\Widgets;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTasksWidget extends BaseWidget
{
    protected static ?string $heading = null;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return __('Urgent Tasks');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->where(function ($query) {
                        $query->whereIn('priority', [4, 5])
                            ->orWhere(function ($q) {
                                $q->whereNotNull('due_date')
                                    ->where('due_date', '<=', now()->addDays(3))
                                    ->where('due_date', '>=', now());
                            });
                    })
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-fire')
                    ->iconColor('danger'),

                TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color()),

                TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color())
                    ->icon(fn ($state) => $state->icon()),

                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : 'gray')
                    ->icon(fn ($record) => $record->isOverdue() ? 'heroicon-o-exclamation-triangle' : null)
                    ->description(fn ($record) => $record->due_date ? $record->due_date->diffForHumans() : null),

                TextColumn::make('user.name')
                    ->label(__('Assigned To'))
                    ->default('Unassigned')
                    ->icon('heroicon-o-user'),
            ])
            ->defaultSort('due_date', 'asc')
            ->emptyStateHeading(__('No urgent tasks'))
            ->emptyStateDescription(__('Great! You have no urgent tasks at the moment.'))
            ->emptyStateIcon('heroicon-o-check-circle');
    }

    public static function canView(): bool
    {
        return auth()->user()?->isUser() ?? false;
    }
}
