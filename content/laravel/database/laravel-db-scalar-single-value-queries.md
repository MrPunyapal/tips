---
category: "Laravel"
tags: ["Laravel", "Database", "Queries", "Performance"]
date: "2023-07-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Retrieve Single Primitive Values Directly with DB::scalar()

> Use DB::scalar() to execute raw SQL queries that return a single scalar value without array wrapping.

When running aggregate SQL functions, database version checks, or single-value calculations (such as `SELECT count(*)` or `SELECT current_timestamp`), `DB::select()` returns an array of objects (`$result[0]->count`).

`DB::scalar()` extracts and returns the single primitive value directly.

## Querying Scalar Values

```php
use Illuminate\Support\Facades\DB;

// Returns integer count directly: 42
$count = DB::scalar('SELECT count(*) FROM users WHERE is_active = 1');

// Returns string version directly: "8.0.36"
$version = DB::scalar('SELECT version()');

// Returns boolean or timestamp directly
$databaseTime = DB::scalar('SELECT NOW()');
```

## Parameter Bindings

`DB::scalar()` supports parameter bindings for SQL injection protection:

```php
$totalRevenue = DB::scalar(
    'SELECT SUM(total) FROM orders WHERE status = ? AND created_at >= ?',
    ['completed', now()->startOfYear()]
);
```

## Summary

- Returns single primitive values (int, string, float, bool) directly.
- Eliminates manual `$result[0]->column` array extraction boilerplate.
- Fully supports prepared parameter bindings.
