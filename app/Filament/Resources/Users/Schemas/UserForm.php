<?php

namespace App\Filament\Resources\Users\Schemas;

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
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('role')
                            ->label(__('Role'))
                            ->options([
                                'user' => __('User'),
                                'admin' => __('Admin'),
                            ])
                            ->required()
                            ->default('user')
                            ->native(false),

                        Select::make('language')
                            ->label(__('Language'))
                            ->options([
                                'en' => __('English'),
                                'fr' => __('French'),
                            ])
                            ->required()
                            ->default('en')
                            ->native(false),

                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->revealable(),
                    ])->columns(2),
            ]);
    }
}
