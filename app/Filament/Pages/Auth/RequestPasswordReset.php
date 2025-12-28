<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\RequestPasswordReset as PasswordResetRequestPasswordReset;

class RequestPasswordReset extends PasswordResetRequestPasswordReset
{
    protected string $view = 'filament.pages.auth.request-password-reset';
}
