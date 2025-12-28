<x-filament-panels::page.simple>
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
    <style>
        .auth-form-container label,
        .auth-form-container label *,
        .auth-form-container .fi-fo-field-wrp-label,
        .auth-form-container .fi-fo-field-wrp-label * {
            color: #111827 !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
    @endpush

    <div class="auth-wrapper">
        <div class="auth-layout-grid">
            <div class="auth-brand-section">
                <div class="auth-logo-container">
                    <img src="/images/logo.svg" class="auth-logo-icon" alt="TaskFlow Logo"/>
                    <h1 class="auth-brand-name">TaskFlow</h1>
                </div>

                <p class="auth-tagline">
                    {{ __('Manage your projects easily') }} 💝 👍
                </p>
            </div>

            <div class="auth-form-section">
                <div class="auth-form-container">
                    <div class="auth-mobile-logo">
                        <img src="/images/logo.svg" class="auth-mobile-logo-icon" alt="TaskFlow"/>
                    </div>

                    <h2 class="auth-form-title">{{ __('Reset Password') }}</h2>

                    <form wire:submit="request" class="auth-form">
                        {{ $this->form }}

                        <div class="auth-notice-box">
                            <p class="auth-notice-text">
                                {{ __("Enter your email and we'll send you a password reset link. Check your spam folder if you don't receive the email.") }}
                            </p>
                        </div>

                        <x-filament::button type="submit" size="lg" class="w-full">
                            {{ __('Send Reset Link') }}
                        </x-filament::button>

                        <div class="auth-footer-text">
                            {{ __('By continuing, you accept our') }}
                            <a href="#" class="auth-footer-link">{{ __('privacy policy') }}</a>.
                        </div>
                    </form>

                    <div class="auth-alternate-link">
                        <a href="{{ route('filament.admin.auth.login') }}">
                            {{ __('Back to Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page.simple>
