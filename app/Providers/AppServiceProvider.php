<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        $appUrl = rtrim((string) config('app.url'), '/');

        if ($appUrl !== '') {
            // За reverse proxy signed URL Livewire/Filament должны строиться от APP_URL,
            // а не от внутреннего host контейнера (localhost:8080).
            URL::forceRootUrl($appUrl);

            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }
    }
}
