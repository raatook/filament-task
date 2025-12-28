<?php

namespace App\Filament\Widgets;

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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'done' => __('Done'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'done' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => __('Low'),
                        2 => __('Medium'),
                        3 => __('High'),
                        4 => __('Urgent'),
                        5 => __('Critical'),
                        default => (string) $state,
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1, 2 => 'success',
                        3 => 'warning',
                        4, 5 => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (int $state): string => match ($state) {
                        4, 5 => 'heroicon-o-arrow-up',
                        3 => 'heroicon-o-minus',
                        1, 2 => 'heroicon-o-arrow-down',
                        default => 'heroicon-o-minus',
                    }),

                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->due_date && $record->due_date->isPast() ? 'danger' : 'gray')
                    ->icon(fn ($record) => $record->due_date && $record->due_date->isPast() ? 'heroicon-o-exclamation-triangle' : null)
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
