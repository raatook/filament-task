<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage(__('Email copied to clipboard')),

                TextColumn::make('role')
                    ->label(__('Role'))
                    ->badge()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->role->getDescription()),

                TextColumn::make('language')
                    ->label(__('Language'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'en' => __('English'),
                        'fr' => __('French'),
                        default => $state,
                    })
                    ->color('info')
                    ->sortable(),

                TextColumn::make('assigned_projects_count')
                    ->label(__('Projects'))
                    ->counts('assignedProjects')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-briefcase'),

                TextColumn::make('tasks_count')
                    ->counts('tasks')
                    ->label(__('Tasks'))
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-clipboard-document-list'),

                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('Role'))
                    ->options(fn () => collect(UserRole::cases())
                        ->mapWithKeys(fn ($role) => [$role->value => $role->getLabel()])
                    ),

                SelectFilter::make('language')
                    ->label(__('Language'))
                    ->options([
                        'en' => __('English'),
                        'fr' => __('French'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (User $record) => $record->id !== auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
