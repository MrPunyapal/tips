---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2023-06-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Query Primary Keys Dynamically with whereKey() and whereKeyNot()

> Use whereKey() and whereKeyNot() to query model primary keys automatically without hardcoding 'id' column names.

Models with custom primary keys (such as `protected $primaryKey = 'uuid';` or `'account_id'`) can break queries if you hardcode `where('id', $key)` or `whereIn('id', $keys)`.

`whereKey()` and `whereKeyNot()` automatically query the model's configured primary key column.

## Querying by Single Key or Array of Keys

```php
use App\Models\Order;

// Resolves automatically to: WHERE order_id = 42
$order = Order::whereKey(42)->first();

// Resolves automatically to: WHERE order_id IN (1, 2, 3)
$orders = Order::whereKey([1, 2, 3])->get();
```

## Excluding Primary Keys with whereKeyNot()

```php
// Find all active users except the current authenticated user
$otherUsers = User::whereKeyNot(auth()->id())
    ->where('is_active', true)
    ->get();
```

## Summary

- Automatically respects custom `$primaryKey` model configurations.
- Accepts single IDs, arrays of IDs, or Eloquent collections.
- `whereKeyNot()` cleanly excludes primary key matches.
