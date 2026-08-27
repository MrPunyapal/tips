---
category: "Laravel"
tags: ["Laravel", "Collections", "Clean Code"]
date: "2023-09-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Build Conditional Transformation Pipelines with Collection::when() and whenEmpty()

> Use when(), unless(), and whenEmpty() on Laravel Collections to conditionally apply transformations without breaking method chains.

When processing collections (such as applying search filters or sorting parameters), writing intermediate `if` blocks breaks the fluent chain and requires temporary variables.

Laravel Collections provide `when()` and `unless()` to conditionally execute closures.

## Conditional Transformations with when()

```php
$sortBy = request('sort'); // 'price', 'rating', or null
$filterActive = request()->boolean('active_only');

$products = collect($rawProducts)
    ->when($filterActive, function ($collection) {
        return $collection->where('is_active', true);
    })
    ->when($sortBy === 'price', function ($collection) {
        return $collection->sortBy('price');
    })
    ->when($sortBy === 'rating', function ($collection) {
        return $collection->sortByDesc('rating');
    });
```

## Fallback Data with whenEmpty()

When a collection contains no items, `whenEmpty()` provides a clean fallback closure:

```php
$recipients = collect($users)
    ->whenEmpty(function ($collection) {
        // Fallback to default administrator if no recipients found
        return collect([User::getSystemAdmin()]);
    });
```

## Summary

- Keeps collection transformation logic inside a single readable fluent chain.
- Accepts an optional default callback as the third argument if the condition is false.
- `whenEmpty()` cleanly handles empty dataset fallbacks.
