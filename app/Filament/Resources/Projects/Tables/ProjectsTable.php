<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Project Name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('tasks_count')
                    ->label(__('Tasks'))
                    ->counts('tasks')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('pending_tasks')
                    ->label(__('Pending'))
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn ($record) => $record->tasks()->where('status', 'pending')->count())
                    ->toggleable(),

                TextColumn::make('in_progress_tasks')
                    ->label(__('In Progress'))
                    ->badge()
                    ->color('warning')
                    ->getStateUsing(fn ($record) => $record->tasks()->where('status', 'in_progress')->count())
                    ->toggleable(),

                TextColumn::make('done_tasks')
                    ->label(__('Done'))
                    ->badge()
                    ->color('success')
                    ->getStateUsing(fn ($record) => $record->tasks()->where('status', 'done')->count())
                    ->toggleable(),

                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date && $record->due_date->isPast() ? 'danger' : null),

                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
