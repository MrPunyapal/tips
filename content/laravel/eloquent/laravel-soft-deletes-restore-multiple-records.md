---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Maintenance"]
date: "2023-06-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Bulk Restore Soft-Deleted Models in a Single Query

> Restore multiple soft-deleted Eloquent models simultaneously using restore() on a withTrashed() query builder.

When an admin restores multiple archived records (such as restoring all posts by a reactivated author or recovering batch-deleted invoices), looping through records and calling `$model->restore()` on each instance executes separate SQL updates.

You can restore matching records in a single database query.

## Bulk Restoring Records

Chain `restore()` directly onto a `withTrashed()` or `onlyTrashed()` query builder:

```php
use App\Models\Post;

// Restores all soft-deleted posts for a specific user in a single SQL UPDATE query
Post::onlyTrashed()
    ->where('user_id', $user->id)
    ->restore();
```

## Restoring Records by Primary Key List

```php
$postIds = [12, 15, 18, 22];

// Restore specific IDs in bulk
Post::onlyTrashed()
    ->whereIn('id', $postIds)
    ->restore();
```

## Summary

- Executes a single `UPDATE table SET deleted_at = NULL WHERE ...` query.
- Eliminates the memory and database overhead of fetching and updating individual model instances.
- Must be called on `onlyTrashed()` or `withTrashed()` builder instances.
