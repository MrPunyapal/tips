---
category: "Laravel"
tags: ["Laravel", "Collections", "Validation", "Clean Code"]
date: "2024-10-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Verify Entire Datasets with Collection::every()

> Use every() on Laravel Collections to verify whether every single item in a collection satisfies a truth test.

When validating whether all line items in an order are in stock, or confirming all members in a team have completed onboarding, looping and keeping boolean flags is error-prone.

The `every()` method evaluates a predicate callback across all items.

## Basic Usage

```php
$scores = collect([85, 92, 78, 90]);

// Returns true if all scores are >= 70
$allPassed = $scores->every(fn (int $score) => $score >= 70);
```

## Checking Model Collections

```php
$orderItems = $order->items;

// True only if every single item is in stock
$canFulfill = $orderItems->every(fn (OrderItem $item) => $item->quantity <= $item->product->stock);
```

## Truthy Value Checks

If no callback is provided, `every()` checks if all elements are truthy:

```php
$flags = collect([true, true, true]);
$allTrue = $flags->every(); // true
```

## Summary

- Returns `true` if all items pass the condition; returns `false` immediately on the first failed item (short-circuiting).
- Returns `true` on empty collections.
- Replaces manual `foreach` loops and state tracking flags.
