---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Collections", "Database"]
date: "2025-07-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Convert Eloquent Collections Back into Query Builders with toQuery()

> Use toQuery() on Eloquent collections to convert a set of in-memory models back into an Eloquent query builder for bulk operations.

When you have loaded a collection of models (e.g. through filtering or sorting) and now need to perform a bulk database operation on those exact models (such as mass-updating a column or eager-loading a relationship), querying by IDs manually with `Model::whereIn('id', $collection->pluck('id'))` is verbose.

The `toQuery()` method generates an Eloquent builder instance scoped to the collection's primary keys.

## Bulk Updates via toQuery()

```php
use App\Models\User;

$inactiveUsers = User::where('last_login_at', '<', now()->subYear())->get();

// Convert collection back into query builder to perform a bulk SQL update
$inactiveUsers->toQuery()->update([
    'status' => 'archived',
]);
```

## Eager Loading Relationships on Filtered Collections

```php
// Eager-load relations across the collection via SQL
$inactiveUsers->toQuery()->with('profile')->get();
```

## Summary

- Converts an in-memory `Eloquent\Collection` into a scoped `Builder` instance.
- Automatically handles `WHERE IN` constraints matching the collection's primary keys.
- Ideal for mass updates and bulk deletions on pre-filtered models.
