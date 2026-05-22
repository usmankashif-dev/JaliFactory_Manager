<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in all non-local environments (production, staging, etc.)
        // This is essential for proper asset URL generation behind reverse proxies (Render)
        if (!$this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
