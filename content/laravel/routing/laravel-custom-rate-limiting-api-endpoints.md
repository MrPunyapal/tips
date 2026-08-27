---
category: "Laravel"
tags: ["Laravel", "Routing", "Security", "Rate Limiting"]
date: "2023-08-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Define Custom API and Authentication Rate Limiters with RateLimiter::for()

> Configure dynamic rate limiting thresholds by IP, user ID, or subscription tier using RateLimiter::for() in AppServiceProvider.

Protecting public APIs, login endpoints, and export triggers from brute-force attacks and abuse requires configurable rate limiting rules.

Laravel provides the `RateLimiter` facade to define named rate limiters with custom throttling strategies.

## Defining Rate Limiters in AppServiceProvider

```php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Dynamic API rate limiting based on subscription tier
        RateLimiter::for('api', function (Request $request) {
            return $request->user()?->is_premium
                ? Limit::perMinute(500)->by($request->user()->id)
                : Limit::perMinute(60)->by($request->ip());
        });

        // 2. Strict rate limiter for sensitive authentication endpoints
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json(['message' => 'Too many login attempts.'], 429, $headers);
                });
        });
    }
}
```

## Applying Limiters to Routes

Attach the `throttle` middleware with the configured limiter name:

```php
Route::middleware('throttle:api')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index']);
});

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');
```

## Summary

- `Limit::perMinute()` sets requests per minute, hour, or day.
- `->by()` partitions rate limits by user ID, IP address, or custom compound keys.
- `->response()` customizes the 429 response structure.
