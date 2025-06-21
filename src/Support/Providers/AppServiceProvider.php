<?php

namespace Support\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Google\Client as GoogleClient; // Added import

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        URL::forceScheme(config('app.protocol'));

        $this->app->singleton(GoogleClient::class, function ($app) {
            $client = new GoogleClient();
            // Client ID, Secret, and Redirect URI are set in GoogleAuthController constructor.
            // If needed globally, they could be set here using config('services.google.client_id'), etc.
            return $client;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme(config('app.protocol'));
    }
}
