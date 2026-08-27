---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2025-08-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Retrieve Multiple Models by Primary Key with findMany()

> Use findMany() or find([$id1, $id2]) to fetch a collection of models by their primary keys without writing whereIn('id', ...) queries.

When looking up a list of records by their IDs (such as loading selected product IDs from a shopping cart array), writing `Product::whereIn('id', $ids)->get()` is verbose and bypasses the model's primary key mapping.

Eloquent's `findMany()` retrieves multiple models cleanly.

## Basic Usage

```php
use App\Models\Product;

$productIds = [10, 25, 42];

// Clean multi-model lookup
$products = Product::findMany($productIds);

// Or pass an array directly to find()
$products = Product::find([10, 25, 42]);
```

## Selecting Specific Columns

Pass the desired column list as the second argument:

```php
$products = Product::findMany([10, 25], ['id', 'name', 'price']);
```

## Summary

- Respects custom model `$primaryKey` definitions automatically.
- Returns an `Eloquent\Collection` containing all found matching instances.
- Shorthand for `whereIn($primaryKey, $ids)->get()`.
