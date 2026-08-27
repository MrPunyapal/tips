---
category: "Laravel"
tags: ["Laravel", "Exceptions", "Clean Code", "Utilities"]
date: "2023-01-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Catch Exceptions and Return Fallbacks Cleanly with rescue()

> Use the rescue() helper to execute risky operations, catch exceptions, and return fallback values without writing full try-catch blocks.

When calling external APIs, parsing third-party feeds, or reading optional configuration where failure should not crash the request, wrapping operations in full `try { ... } catch (Throwable $e) { ... }` blocks adds boilerplate.

Laravel's `rescue()` helper catches exceptions and returns a default fallback.

## Basic Usage

```php
// Returns the API response or null if an exception occurs
$geo = rescue(function () use ($ip) {
    return Http::timeout(2)->get("https://ip-api.com/json/{$ip}")->json();
});
```

## Providing Default Fallback Values

Pass a static value or a fallback closure as the second argument:

```php
$rates = rescue(
    fn () => Http::get('https://api.rates.com/live')->json(),
    fn () => Cache::get('cached_rates', [])
);
```

## Disabling Exception Reporting

By default, `rescue()` reports the exception to your logging handler. Pass `report: false` to suppress logging:

```php
$avatar = rescue(fn () => $user->fetchGravatar(), 'default-avatar.png', report: false);
```

## Summary

- Executes closures safely and catches any `Throwable` exception.
- Returns custom fallback values or fallback closure results.
- `report` argument controls whether caught exceptions are logged to storage.
