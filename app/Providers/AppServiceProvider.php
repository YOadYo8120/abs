<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;

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
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Configure password reset URL
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            $url = config('app.url') . '/reset-password/' . $token;
            $url .= '?email=' . urlencode($notifiable->email);
            return $url;
        });
    }
}
