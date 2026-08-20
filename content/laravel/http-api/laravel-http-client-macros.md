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

When interacting with external APIs in a Laravel application, developers often repeat identical HTTP setup (such as API keys, base URLs, timeouts, and JSON headers) across multiple controllers, jobs, and service classes:

```php
// Repeated across multiple call sites
$customer = Http::withToken(config('services.payments.secret'))
    ->baseUrl(config('services.payments.url'))
    ->acceptJson()
    ->timeout(10)
    ->get("/customers/{$customerId}");

$refund = Http::withToken(config('services.payments.secret'))
    ->baseUrl(config('services.payments.url'))
    ->acceptJson()
    ->timeout(10)
    ->post('/refunds', ['payment_id' => $paymentId]);
```

Repeating this configuration creates duplication, increases the risk of inconsistent headers or timeouts, and makes updating credentials or URLs tedious.

Laravel's HTTP client supports macros via `Http::macro()`, allowing you to define your service setup once and reuse the pre-configured client anywhere.

## Define Once, Reuse Anywhere

An HTTP macro does not need to execute the request immediately. Instead, the macro can return a configured `PendingRequest` instance that callers can continue chaining with standard methods (`get()`, `post()`, `withHeaders()`, etc.):

```php
use Illuminate\Support\Facades\Http;

// Define the API setup once (e.g. in AppServiceProvider)
Http::macro('payments', function () {
    return Http::withToken(config('services.payments.secret'))
        ->baseUrl(config('services.payments.url'))
        ->acceptJson()
        ->timeout(10);
});

// Reuse the configured client anywhere in your application
$customer = Http::payments()
    ->get("/customers/{$customerId}");

$refund = Http::payments()
    ->post('/refunds', [
        'payment_id' => $paymentId,
        'amount'     => 4900,
    ]);
```

## Centralized Registration in Service Providers

Register your HTTP macros centrally within your application's service provider boot lifecycle:

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
            ->timeout(10));

        Http::macro('shipping', fn () => Http::withHeaders([
            'X-API-Key' => config('services.shipping.key'),
            'X-Account-ID' => config('services.shipping.account_id'),
        ])->baseUrl(config('services.shipping.url'))->acceptJson());
    }
}
```

## What Belongs in the Macro vs Call Site

To keep macros clean and flexible, separate shared infrastructure configuration from request-specific logic:

- **Inside the Macro**:
  - Base URL (`baseUrl()`)
  - Authentication tokens or API keys (`withToken()`, `withBasicAuth()`)
  - Shared headers (`acceptJson()`, custom organizational headers)
  - Default connection timeouts or retry policies (`timeout()`, `retry()`)

- **At the Call Site**:
  - Specific endpoints and path parameters (`/orders/{id}`)
  - HTTP verbs (`get()`, `post()`, `put()`, `delete()`)
  - Request payloads and query parameters
  - Contextual error handling (`throw()`, `onError()`)

## Macros vs Dedicated Service Classes

HTTP macros provide a lightweight abstraction that avoids boilerplate for small to medium third-party integrations. However, consider the scope of your integration:

- **Use HTTP Macros when**: You need consistent credentials, base URLs, and headers across a few endpoints, and standard HTTP response methods (`$response->json()`, `$response->successful()`) are sufficient.
- **Use Dedicated Service Classes when**: The integration requires complex domain logic, custom data transfer objects (DTOs), webhook verification, or multi-step token exchange workflows.

## Summary

- Use `Http::macro()` to centralize repeated API connection setup into named, fluent methods.
- Return the configured `Http` instance from the macro closure so calling code retains full HTTP client chaining capabilities.
- Keep credentials and URLs in `config/services.php` rather than hardcoding them inside the macro.
