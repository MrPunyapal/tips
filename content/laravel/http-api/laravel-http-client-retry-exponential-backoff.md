---
category: "Laravel"
tags: ["Laravel", "HTTP", "API", "Resilience"]
date: "2023-07-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Handle Transient Network Failures with Http::retry()

> Configure automatic retries with exponential backoff and condition closures on the Laravel HTTP client to handle temporary API rate limits and network drops.

External APIs occasionally experience transient network hiccups, dropped connections, or momentary HTTP 429 / 503 errors.

The Laravel HTTP client provides `retry()` to automatically retry failed requests.

## Basic Retry Configuration

```php
use Illuminate\Support\Facades\Http;

// Retries up to 3 times, waiting 100ms between attempts
$response = Http::retry(3, 100)->get('https://api.example.com/rates');
```

## Exponential Backoff with Multiplier

Avoid overwhelming third-party servers by increasing sleep duration between each retry attempt:

```php
// Retries up to 3 times:
// 1st retry: waits 100ms
// 2nd retry: waits 200ms
// 3rd retry: waits 400ms
$response = Http::retry(3, 100, throw: false, useExponentialBackoff: true)
    ->get('https://api.example.com/data');
```

## Conditional Retries with Callbacks

Retry only on specific status codes (such as retrying on 503 Service Unavailable or 429 Too Many Requests, but failing immediately on 401 Unauthorized):

```php
$response = Http::retry(3, 100, function ($exception, $request) {
    // Retry only if it was a connection exception or server error
    return $exception instanceof IlluminateHttpClientConnectionException
        || $exception->response?->status() === 429
        || $exception->response?->serverError();
})->post('https://api.payment-gateway.com/charge', $payload);
```

## Summary

- Automatically retries dropped connections and server errors.
- `useExponentialBackoff` doubles wait times between attempts to prevent thundering herd problems.
- Callback closures allow selective retries based on error types.
