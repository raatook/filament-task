<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
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
                    ->options(fn () => collect(TaskStatus::cases())
                        ->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])
                    )
                    ->default(TaskStatus::PENDING->value)
                    ->required()
                    ->native(false),

                Select::make('priority')
                    ->label(__('Priority'))
                    ->options(fn () => collect(TaskPriority::cases())
                        ->mapWithKeys(fn ($priority) => [$priority->value => $priority->getLabel()])
                    )
                    ->default(TaskPriority::LOW->value)
                    ->required()
                    ->native(false)
                    ->helperText(fn ($state) =>
                        $state ? TaskPriority::from($state)?->getDescription() : null
                    ),

                DatePicker::make('due_date')
                    ->label(__('Due Date'))
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder(__('Select due date'))
                    ->minDate(today())
                    ->helperText(__('Tasks with due dates within 3 days will be marked as urgent')),
            ]);
    }
}
