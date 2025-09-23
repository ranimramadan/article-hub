<?php
namespace App\Providers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

function configureRateLimiting(): void
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
    });

    
    RateLimiter::for('auth', function (Request $request) {
        return Limit::perMinute(10)->by($request->ip());
    });
}
