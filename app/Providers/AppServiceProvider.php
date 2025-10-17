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

        // Force HTTPS URLs if APP_URL uses https to avoid mixed content (eg. Scribe assets)
        $appUrl = (string) config('app.url');
        if (str_starts_with($appUrl, 'https://')) {
            URL::forceScheme('https');
        }
    }
}
