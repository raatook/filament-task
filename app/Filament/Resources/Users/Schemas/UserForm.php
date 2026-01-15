<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('User Information'))
                    ->description(__('Basic user information and credentials'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Enter full name')),

                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder(__('user@example.com')),

                        Select::make('role')
                            ->label(__('Role'))
                            ->options(fn () => collect(UserRole::cases())
                                ->mapWithKeys(fn ($role) => [$role->value => $role->getLabel()])
                            )
                            ->required()
                            ->default(UserRole::USER->value)
                            ->native(false)
                            ->helperText(fn ($state) =>
                                $state ? UserRole::from($state)?->getDescription() : null
                            ),

                        Select::make('language')
                            ->label(__('Language'))
                            ->options([
                                'en' => __('English'),
                                'fr' => __('French'),
                            ])
                            ->required()
                            ->default('en')
                            ->native(false)
                            ->helperText(__('Preferred language for the interface')),

                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->revealable()
                            ->helperText(__('Leave blank to keep the current password'))
                            ->placeholder(__('Enter a secure password')),
                    ])->columns(2),
            ]);
    }
}
