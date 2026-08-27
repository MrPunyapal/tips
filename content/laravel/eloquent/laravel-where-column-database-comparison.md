---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Queries"]
date: "2022-11-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Compare Two Database Columns in Queries with whereColumn()

> Use whereColumn() to compare two database columns against each other directly in SQL without writing raw query expressions.

Standard `where()` clauses compare a database column against a static PHP value. When you need to verify that one table column is greater than, equal to, or different from another column in the same row, use `whereColumn()`.

## Basic Equality Check

```php
use App\Models\User;

// Finds users whose first_name matches their last_name
$users = User::whereColumn('first_name', 'last_name')->get();
```

## Comparison Operators

Pass an operator as the second argument to check for inequality, timestamps, or numerical thresholds:

```php
use App\Models\Order;

// Orders updated after creation
$modifiedOrders = Order::whereColumn('updated_at', '>', 'created_at')->get();

// Invoices where paid amount is less than total amount
$pendingInvoices = Invoice::whereColumn('paid_amount', '<', 'total_amount')->get();
```

## Comparing Multiple Column Pairs

Pass an array of column pairs to verify multiple conditions in a single call:

```php
$records = Order::whereColumn([
    ['updated_at', '>', 'created_at'],
    ['discount_amount', '<', 'subtotal_amount'],
])->get();
```

## Summary

- Formats queries as `WHERE col1 = col2` instead of binding values with placeholders.
- Supports all standard SQL comparison operators (`=`, `>`, `<`, `>=`, `<=`, `<>`).
- Cleanly replaces raw `whereRaw('updated_at > created_at')` strings with type-safe builder syntax.
