<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('users')
                    ->label(__('Assigned Users'))
                    ->relationship('users', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->isAdmin()),

                TextInput::make('name')
                    ->label(__('Project Name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Enter project name'))
                    ->autofocus(),

                Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(5)
                    ->columnSpanFull()
                    ->placeholder(__('Enter project description (optional)'))
                    ->maxLength(65535),

                DatePicker::make('due_date')
                    ->label(__('Due Date'))
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder(__('Select project due date'))
                    ->minDate(now()),
            ]);
    }
}
