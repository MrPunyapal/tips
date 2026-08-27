---
category: "Laravel"
tags: ["Laravel", "Cache", "Performance", "Architecture"]
date: "2026-03-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Eliminate Cache Stampedes with Cache::flexible() and Stale-While-Revalidate

> Use Cache::flexible() to serve slightly stale cache values immediately to users while refreshing the cache in the background.

When a heavily requested cached item expires, dozens of simultaneous requests encounter a cache miss at the exact same moment. They all execute the heavy database query simultaneously (known as a "cache stampede" or thundering herd problem), causing database spikes and slow response times.

`Cache::flexible()` implements the **Stale-While-Revalidate** caching pattern.

## Using Cache::flexible()

```php
use Illuminate\Support\Facades\Cache;

$products = Cache::flexible('homepage_top_products', [5 * 60, 30 * 60], function () {
    // Heavy aggregate database query
    return Product::where('is_featured', true)
        ->withCount('orders')
        ->orderByDesc('orders_count')
        ->limit(10)
        ->get();
});
```

## How It Works

- **First 5 minutes (Fresh)**: Serves cached value immediately from memory.
- **Between 5 and 30 minutes (Stale)**: Serves the existing cached value immediately to the user (0ms delay) and asynchronously triggers the closure in the background to refresh the cache.
- **After 30 minutes (Expired)**: Executes query synchronously.

## Summary

- Eliminates slow response times caused by cache expiration.
- Prevents database overloading from simultaneous cache misses.
- First array argument defines the fresh TTL and grace period TTL.
