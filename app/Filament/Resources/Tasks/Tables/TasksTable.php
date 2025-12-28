<?php

namespace App\Filament\Resources\Tasks\Tables;

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
                    ->limit(50),

                TextColumn::make('project.name')
                    ->label(__('Project'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'warning',
                        'done' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'done' => __('Done'),
                    })
                    ->sortable(),

                TextColumn::make('priority')
                    ->label(__('Priority'))
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'gray',
                        2 => 'info',
                        3 => 'warning',
                        4 => 'danger',
                        5 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => __('Low'),
                        2 => __('Medium'),
                        3 => __('High'),
                        4 => __('Urgent'),
                        5 => __('Critical'),
                        default => (string) $state,
                    })
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date && $record->due_date->isPast() && $record->status !== 'done' ? 'danger' : null),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('user_id')
                    ->label(__('Assigned To'))
                    ->relationship('user', 'name')
                    ->visible(fn () => auth()->user()?->isAdmin()),

                SelectFilter::make('project_id')
                    ->label(__('Project'))
                    ->relationship('project', 'name'),

                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'done' => __('Done'),
                    ]),

                SelectFilter::make('priority')
                    ->label(__('Priority'))
                    ->options([
                        1 => __('Low'),
                        2 => __('Medium'),
                        3 => __('High'),
                        4 => __('Urgent'),
                        5 => __('Critical'),
                    ]),
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
