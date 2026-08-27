---
category: "Laravel"
tags: ["Laravel", "HTTP", "Performance", "Concurrency"]
date: "2024-04-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Send Concurrent HTTP Requests with Http::pool()

> Use Http::pool() to dispatch multiple external API requests concurrently in parallel rather than waiting for sequential responses.

When your application fetches data from multiple external microservices or third-party APIs (such as fetching user billing data, shipment status, and weather forecasts), executing requests sequentially adds the latency of each API call together.

`Http::pool()` dispatches requests concurrently in parallel using Guzzle's asynchronous curl multi-handler.

## Sending Parallel Requests

```php
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

$responses = Http::pool(fn (Pool $pool) => [
    $pool->as('rates')->get('https://api.example.com/exchange-rates'),
    $pool->as('user')->withToken($token)->get('https://api.example.com/user'),
    $pool->as('status')->get('https://api.example.com/system-status'),
]);

// Responses are accessed by their assigned pool keys
$rates = $responses['rates']->ok() ? $responses['rates']->json() : [];
$user = $responses['user']->json();
```

## Summary

- Executes multiple HTTP requests concurrently in a single non-blocking event loop.
- Reduces total execution time to the duration of the slowest single request.
- Assign custom keys with `$pool->as('key')` for easy response lookup.
