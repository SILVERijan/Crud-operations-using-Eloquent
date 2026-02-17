<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        Paginator::useBootstrapFive();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(150)
            ->by($request->ip())
            ->response(function (Request $request, array $headers) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Too many authentication attempts. Please try again in ' . $headers['Retry-After'] . ' seconds.'], 429);
                }
                                                                                                                                                                                                                                        
                return redirect()->back()
                    ->withInput($request->except('password'))
                    ->with('error', 'Too many attempts. Please try again later.');
            });
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });
    }
}
