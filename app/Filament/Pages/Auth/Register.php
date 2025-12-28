<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as PagesRegister;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class Register extends PagesRegister
{
    protected string $view = 'filament.pages.auth.register';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                Select::make('language')
                    ->label(__('Language'))
                    ->options([
                        'en' => __('English'),
                        'fr' => __('French'),
                    ])
                    ->default('en')
                    ->required()
                    ->native(false),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
