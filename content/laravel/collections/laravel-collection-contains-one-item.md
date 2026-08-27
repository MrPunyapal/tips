---
category: "Laravel"
tags: ["Laravel", "Collections", "Clean Code"]
date: "2024-08-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Check for Exactly One Collection Item with containsOneItem()

> Use containsOneItem() on Laravel Collections instead of count() === 1 to write cleaner, more expressive dataset assertions.

Checking whether a collection or filtered list contains exactly a single item (such as checking if a user has only one registered payment method or verifying single-item cart status) traditionally required `$collection->count() === 1`.

Laravel provides the expressive `containsOneItem()` method.

## Basic Usage

```php
$cart = collect([
    ['id' => 1, 'name' => 'Book', 'price' => 20]
]);

// Clear and readable
if ($cart->containsOneItem()) {
    $item = $cart->first();
    // Apply single-item shipping rate
}
```

## In Authorization or Business Logic

```php
// Prevent deleting the only remaining administrator account
if ($team->admins->containsOneItem()) {
    abort(422, 'Cannot remove the last remaining administrator.');
}
```

## Summary

- Returns `true` only if the collection contains exactly 1 item; returns `false` if empty or containing multiple items.
- More expressive than checking count equality.
- Available on all Laravel `Collection` and `LazyCollection` instances.
