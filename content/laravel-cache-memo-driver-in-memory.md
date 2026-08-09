---
category: "Laravel"
tags: ["Laravel","Cache","Performance"]
date: "2025-10-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Prevent Duplicate Redis and Database Lookups with Cache::memo()

> Use Cache::memo() to combine persistent cache stores with per-request memory caching, preventing repetitive network roundtrips during a single HTTP request.

Calling `Cache::get('key')` multiple times in a single request still incurs a Redis network roundtrip or database query each time.

`Cache::memo()` wraps your configured cache store with an in-memory array cache for the duration of the current request:

```php
use Illuminate\Support\Facades\Cache;

// Standard Cache: 3 Redis network roundtrips
$permissions = Cache::get('user.permissions'); // Redis query
$permissions = Cache::get('user.permissions'); // Redis query
$permissions = Cache::get('user.permissions'); // Redis query

// Cache::memo(): 1 Redis query, subsequent calls read from memory
$permissions = Cache::memo()->get('user.permissions'); // Redis query
$permissions = Cache::memo()->get('user.permissions'); // In-memory hit
$permissions = Cache::memo()->get('user.permissions'); // In-memory hit

// Mutations automatically sync the persistent store and invalidate local memory
Cache::memo()->put('user.status', 'active');
Cache::memo()->increment('page.views');

// Works with specific cache stores
Cache::memo('redis')->remember('expensive-report', 3600, fn () =>
    $this->buildReport()
);
```

- Eliminates redundant cache store queries during heavy request lifecycles
- In-memory values reset automatically at the end of the HTTP request
- Mutation methods (`put`, `increment`, `forget`) keep memory and storage in sync
