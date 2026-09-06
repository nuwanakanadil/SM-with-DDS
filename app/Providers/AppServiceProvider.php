<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('public-results', function (Request $request) {
            $admission = trim($request->string('admission_no')->toString());
            $key = $request->ip().'|'.hash('sha256', strtolower($admission));

            return [
                Limit::perMinute(12)->by($key),
                Limit::perMinute(60)->by($request->ip()),
            ];
        });

        Vite::prefetch(concurrency: 3);
    }
}
