---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance"]
date: "2023-08-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Reduce Memory Usage in Heavy Read Queries with toBase()

> Use toBase() on Eloquent queries to retrieve raw stdClass records without the memory overhead of hydrating full Eloquent models.

When querying tens of thousands of database rows for export or background analytics, hydrating every row into an Eloquent `Model` object consumes significant memory and CPU cycles.

Calling `toBase()` transitions the query from Eloquent to the underlying database query builder, returning lightweight `stdClass` objects.

## Using toBase() for High-Performance Queries

```php
use App\Models\User;

// Returns an IlluminateSupportCollection of stdClass objects
$users = User::where('is_active', true)
    ->select(['id', 'email', 'created_at'])
    ->toBase()
    ->get();

foreach ($users as $user) {
    // $user is a plain stdClass object with near-zero memory footprint
    echo $user->email;
}
```

## Memory Comparison

- **Standard `User::all()`**: Hydrates 50,000 Eloquent instances with model events, casts, and mutators (~80MB RAM).
- **`User::toBase()->get()`**: Returns 50,000 plain PHP objects (~12MB RAM).

## Summary

- Drops Eloquent model hydration for maximum raw read throughput.
- Returns standard `stdClass` objects in collection wrappers.
- Perfect for reporting queries, data exports, and background workers.
