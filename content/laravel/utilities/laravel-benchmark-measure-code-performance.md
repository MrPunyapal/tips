---
category: "Laravel"
tags: ["Laravel", "Performance", "Debugging", "Benchmark"]
date: "2023-01-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Measure Code Execution Time with Benchmark::measure() and dd()

> Use Laravel's Benchmark helper to test and compare execution times of complex queries, algorithms, and closures.

When optimizing database queries, comparing caching strategies, or evaluating algorithm performance, calculating execution time with `microtime(true)` math is cumbersome.

Laravel provides the `Illuminate\Support\Benchmark` class to measure runtime durations.

## Measuring a Single Operation

```php
use App\Models\User;
use Illuminate\Support\Benchmark;

// Measures execution time in milliseconds (e.g. "4.12ms")
$time = Benchmark::measure(function () {
    return User::with('orders.items')->get();
});

logger()->info("Query took {$time}ms to complete.");
```

## Comparing Multiple Approaches with Benchmark::dd()

Pass an associative array of closures to `Benchmark::dd()` to compare competing implementations and inspect the results in the browser or terminal:

```php
use App\Models\User;
use Illuminate\Support\Benchmark;

Benchmark::dd([
    'Eager Loading' => fn () => User::with('posts')->get(),
    'Lazy Loading'  => fn () => User::all()->each->posts,
    'Raw DB Query'  => fn () => DB::table('users')->join('posts', 'users.id', '=', 'posts.user_id')->get(),
], iterations: 10);
```

## Output Example

```text
array:3 [
  "Eager Loading" => "3.45ms"
  "Lazy Loading"  => "18.92ms"
  "Raw DB Query"  => "1.21ms"
]
```

## Summary

- Measures execution duration in milliseconds.
- `iterations` parameter runs the closures multiple times and computes the average duration.
- Ideal for comparing query efficiency and cache optimizations.
