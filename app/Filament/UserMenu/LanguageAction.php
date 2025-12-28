<?php

namespace App\Filament\UserMenu;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class LanguageAction
{
    public static function make(): Action
    {
        return Action::make('language')
            ->label(__('Language'))
            ->icon('heroicon-o-language')
            ->form([
                Select::make('language')
                    ->label(__('Language'))
                    ->options([
                        'en' => __('English'),
                        'fr' => __('French'),
                    ])
                    ->default(fn () => session('locale')
                        ?? auth()->user()?->language
                        ?? config('app.locale'))
                    ->required(),
            ])
            ->action(function (array $data, Action $action) {
                $user = auth()->user();
                if ($user) {
                    $user->language = $data['language'];
                    $user->save();
                }
                session(['locale' => $data['language']]);
                $action->redirect(url()->previous(), navigate: true);
            })
            ->modalWidth('sm')
            ->modalHeading(__('Change Language'))
            ->modalSubmitActionLabel(__('Save'))
            ->modalCancelActionLabel(__('Cancel'));
    }
}
