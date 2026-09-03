---
category: "Laravel"
tags: ["Laravel", "HTTP Client", "API", "Architecture"]
date: "2026-08-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Reusable API Configuration with Laravel HTTP Client Macros

> Use Http::macro() to encapsulate repeated authentication, base URLs, and shared headers into reusable HTTP client instances across your application.

When interacting with external APIs, developers often repeat identical HTTP setup (such as API tokens, base URLs, timeouts, and JSON headers) across multiple controllers and jobs.

Using `Http::macro()`, you can register pre-configured HTTP clients once and reuse them anywhere with full method chaining.

---

## Centralized Registration

Register macros in `AppServiceProvider::boot()` and return the configured `PendingRequest`:

```php
namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Http::macro('payments', fn () => Http::withToken(config('services.payments.secret'))
            ->baseUrl(config('services.payments.url'))
            ->acceptJson()
            ->timeout(10)
            ->retry(3, 100));

        Http::macro('github', fn () => Http::withToken(config('services.github.token'))
            ->baseUrl('https://api.github.com')
            ->withHeaders(['User-Agent' => 'MyLaravelApp']));
    }
}
```

---

## Application Usage

Call the macro as a method directly on the `Http` facade:

```php
use Illuminate\Support\Facades\Http;

// Macro methods retain full fluent HTTP client chaining
$customer = Http::payments()->get("/customers/{$id}")->throw()->json();

$charge = Http::payments()->post('/charges', [
    'amount'   => 4900,
    'currency' => 'usd',
]);
```

---

## Practical Guidelines

- **Return the Client**: Always return the pending request from the macro closure so callers can chain standard HTTP verbs (`get()`, `post()`), query params, or error handlers (`throw()`).
- **Configuration Boundary**: Read credentials from `config('services...')` rather than `env()`.
- **Testing**: Macros integrate with `Http::fake()`, allowing you to mock endpoints without altering macro definitions.
