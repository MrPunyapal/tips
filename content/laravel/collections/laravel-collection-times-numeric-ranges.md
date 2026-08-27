---
category: "Laravel"
tags: ["Laravel", "Collections", "Clean Code"]
date: "2025-04-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Generate Iterative Collections Quickly with Collection::times()

> Use Collection::times() to generate numeric-sequence collections or execute closures N times fluently without writing for loops.

When creating dummy datasets, generating pagination page numbers, or creating test fixtures, writing `for ($i = 1; $i <= 10; $i++)` loops creates boilerplate.

`Collection::times()` creates a collection by invoking a callback a given number of times.

## Generating Range Collections

```php
use Illuminate\Support\Collection;

// Generates: [1, 2, 3, 4, 5]
$numbers = Collection::times(5);
```

## Transforming Items During Generation

Pass a closure to transform each increment step:

```php
$months = Collection::times(12, function ($number) {
    return now()->startOfYear()->addMonths($number - 1)->format('F');
});

// Output: ['January', 'February', 'March', ..., 'December']
```

## In Testing and Seeding

```php
// Generate 10 dummy invoice line items
$items = Collection::times(10, fn ($i) => [
    'item_code' => "SKU-{$i}",
    'price'     => $i * 15,
]);
```

## Summary

- Generates 1-indexed numeric collections fluently.
- Accepts transformation closures receiving the current iteration number.
- Clean alternative to `range()` wrapped in `collect()`.
