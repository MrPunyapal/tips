---
category: "Laravel"
tags: ["Laravel", "Collections", "Performance"]
date: "2023-10-04"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Split Collections into Two Groups with Collection::partition()

> Use partition() to divide a collection into two separate collections based on a single boolean truth test in a single pass.

When you need to separate a dataset into two distinct categories (such as paid vs unpaid orders, or active vs inactive subscribers), running two separate `filter()` calls iterates over the collection twice.

`partition()` divides the collection into two collections in a single iteration.

## Partitioning Data with Array Destructuring

```php
use App\Models\User;

$users = User::all();

// Splits into [$passed, $failed] in one operation
[$activeUsers, $inactiveUsers] = $users->partition(function (User $user) {
    return $user->is_active;
});

// $activeUsers contains users where is_active === true
// $inactiveUsers contains users where is_active === false
```

## High-Order Message Syntax

When checking a boolean model method or attribute, you can use higher-order collection syntax:

```php
[$paidInvoices, $unpaidInvoices] = $invoices->partition->isPaid();
```

## Summary

- Divides a collection into two sets in a single (O(N)) iteration pass.
- Returns an array containing two collections, perfectly paired with array destructuring (`[$trueSet, $falseSet]`).
- Supports both closure callbacks and higher-order property proxies.
