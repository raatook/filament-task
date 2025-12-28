<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('Assigned To'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => ! auth()->user()->isAdmin())
                    ->visible(fn () => auth()->user()->isAdmin())
                    ->live(),

                Select::make('project_id')
                    ->label(__('Project'))
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->rows(3),
                        DatePicker::make('due_date')
                            ->label(__('Due Date')),
                    ]),

                TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Enter task title')),

                Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(5)
                    ->columnSpanFull()
                    ->placeholder(__('Enter task description (optional)')),

                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'done' => __('Done'),
                    ])
                    ->default('pending')
                    ->required()
                    ->native(false),

                Select::make('priority')
                    ->label(__('Priority'))
                    ->options([
                        1 => '1 - '.__('Low'),
                        2 => '2 - '.__('Medium'),
                        3 => '3 - '.__('High'),
                        4 => '4 - '.__('Urgent'),
                        5 => '5 - '.__('Critical'),
                    ])
                    ->default(1)
                    ->required()
                    ->native(false),

                DatePicker::make('due_date')
                    ->label(__('Due Date'))
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder(__('Select due date')),
            ]);
    }
}
