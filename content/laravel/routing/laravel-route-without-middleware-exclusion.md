---
category: "Laravel"
tags: ["Laravel", "Routing", "Middleware", "Security"]
date: "2023-03-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Exclude Specific Middleware from Route Groups with withoutMiddleware()

> Use withoutMiddleware() to opt individual routes out of inherited group middleware without breaking your route group structure.

When organizing routes in middleware groups (such as applying `auth` or `verified` to an entire administration prefix), certain individual routes (like public webhooks or guest invitations) might need to bypass specific middleware.

Instead of breaking routes into separate fragmented group blocks, Laravel provides the `withoutMiddleware()` method.

## Excluding Middleware on Individual Routes

```php
use App\Http\Controllers\WebhookController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/settings', [SettingsController::class, 'index']);

    // Public payment webhook that needs to bypass CSRF and Auth checks
    Route::post('/stripe/webhook', [WebhookController::class, 'handle'])
        ->withoutMiddleware(['auth', VerifyCsrfToken::class]);
});
```

## Usage on Resource Controllers

```php
Route::resource('posts', PostController::class)
    ->withoutMiddleware(['auth'], ['only' => ['index', 'show']]);
```

## Summary

- Preserves unified route group structures while allowing selective middleware bypasses.
- Accepts middleware class names or registered alias strings.
- Can be scoped to specific resource controller actions via the second argument.
