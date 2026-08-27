---
category: "Laravel"
tags: ["Laravel", "HTTP", "API", "Error Handling"]
date: "2023-08-02"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Throw Exceptions on HTTP Client Errors with throw() and throwIf()

> Use throw(), throwIf(), and throwUnless() on Laravel HTTP client responses to handle 4xx and 5xx API errors with standard exception workflows.

By default, Laravel's `Http` client does not throw exceptions when receiving 4xx or 5xx HTTP response codes. Calling `$response->json()` on a 404 or 500 response returns the error body rather than failing.

Using `->throw()` forces the client to throw an `Illuminate\Http\Client\RequestException` on error responses.

## Basic Usage

```php
use Illuminate\Support\Facades\Http;

// Throws RequestException automatically if status code is >= 400
$data = Http::get('https://api.example.com/user/profile')
    ->throw()
    ->json();
```

## Conditional Exceptions with throwIf()

Throw exceptions only under specific business conditions:

```php
$response = Http::post('https://api.example.com/order', $payload);

// Throws exception if response status is 422 or if custom error key exists
$response->throwIf(function ($response) {
    return $response->status() === 422 || $response->json('has_error') === true;
});
```

## Custom Error Callbacks

Inspect error details before throwing:

```php
$response = Http::get('https://api.example.com/data')
    ->throw(function ($response, $exception) {
        logger()->error('API request failed: ' . $response->body());
    });
```

## Summary

- `throw()` converts 4xx and 5xx responses into catchable `RequestException` instances.
- Access response details via `$exception->response->status()` and `$exception->response->body()`.
- Keeps API integration code clean and compatible with standard `try-catch` blocks.
