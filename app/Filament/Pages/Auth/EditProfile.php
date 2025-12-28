<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as PagesEditProfile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditProfile extends PagesEditProfile
{
    protected static string $layout = 'filament-panels::components.layout.index';

    protected static bool $isSimple = false;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal Information'))
                    ->description(__('Update your profile information.'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Full Name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),

                        TextInput::make('email')
                            ->label(__('Email Address'))
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('language')
                            ->label(__('Language'))
                            ->options([
                                'en' => __('English'),
                                'fr' => __('French'),
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make(__('Account Security'))
                    ->description(__('Change your password to secure your account.'))
                    ->schema([
                        TextInput::make('current_password')
                            ->label(__('Current Password'))
                            ->password()
                            ->revealable()
                            ->currentPassword()
                            ->dehydrated(fn ($state) => filled($state)),

                        TextInput::make('password')
                            ->label(__('New Password'))
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null),

                        TextInput::make('password_confirmation')
                            ->label(__('Confirm Password'))
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save changes'))
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }
}
