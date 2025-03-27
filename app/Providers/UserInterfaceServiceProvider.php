<?php

namespace App\Providers;

use Filament\Navigation\MenuItem;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\ServiceProvider;

class UserInterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Корректная регистрация хука для меню пользователя в соответствии с требованиями Filament 3
        FilamentView::registerRenderHook(
            'panels::user-menu.before',
            fn (): array => [
                MenuItem::make()
                    ->label(auth()->user()?->getName() ?? '')
                    ->url('#')
                    ->icon('heroicon-m-user'),
            ],
        );
    }
}
