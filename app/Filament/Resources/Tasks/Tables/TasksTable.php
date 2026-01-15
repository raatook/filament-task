<?php

namespace App\Filament\Resources\Tasks\Tables;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('bold'),

                TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->badge()
                    ->color('info'),

                TextColumn::make('user.name')
                    ->label(__('Assigned To'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-user'),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->status->getDescription()),

                TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->priority->getDescription()),

                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null)
                    ->icon(fn ($record) =>
                        $record->isOverdue()
                            ? 'heroicon-o-exclamation-triangle'
                            : 'heroicon-o-calendar'
                    )
                    ->tooltip(fn ($record) =>
                        $record->isOverdue()
                            ? __('This task is overdue!')
                            : null
                    ),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('Assigned To'))
                    ->relationship('user', 'name')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->relationship('project', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(fn () => collect(TaskStatus::cases())
                        ->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])
                    ),

                SelectFilter::make('priority')
                    ->label(__('Priority'))
                    ->options(fn () => collect(TaskPriority::cases())
                        ->mapWithKeys(fn ($priority) => [$priority->value => $priority->getLabel()])
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
