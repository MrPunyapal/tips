---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance"]
date: "2023-11-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Perform High-Throughput Batch Upserts with Eloquent upsert()

> Insert or update hundreds of database rows in a single SQL query using Eloquent's batch upsert() method.

When synchronizing large catalogs, importing CSV files, or updating product inventory in bulk, executing individual `updateOrCreate()` calls inside a loop executes hundreds of separate database roundtrips.

Laravel provides the `upsert()` method to compile batch inserts and updates into a single SQL statement.

## Bulk Upserting Records

```php
use App\Models\Product;

Product::upsert(
    // 1. Array of records to insert or update
    [
        ['sku' => 'PROD-001', 'name' => 'Keyboard', 'price' => 79.99, 'stock' => 15],
        ['sku' => 'PROD-002', 'name' => 'Mouse',    'price' => 49.99, 'stock' => 30],
        ['sku' => 'PROD-003', 'name' => 'Monitor',  'price' => 299.99, 'stock' => 5],
    ],
    // 2. Unique column(s) that identify an existing row
    ['sku'],
    // 3. Columns that should be updated if a matching row exists
    ['price', 'stock']
);
```

## How It Works

- If the `sku` does not exist, the record is inserted.
- If the `sku` matches an existing row, only `price` and `stock` are updated; the `name` column remains untouched.
- Executes as a single `INSERT INTO ... ON DUPLICATE KEY UPDATE` (MySQL) or `ON CONFLICT ... DO UPDATE` (PostgreSQL).

## Summary

- Reduces hundreds of database queries into a single batch execution.
- Second argument specifies the unique/primary key column(s).
- Third argument specifies the exact attributes to update on collision.
