---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Queries"]
date: "2023-06-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Find Models Without Related Records Using doesntHave() and whereDoesntHave()

> Use doesntHave() and whereDoesntHave() to query models that have zero related child records.

Finding orphan records or inactive users (such as users who have never placed an order, or posts with no comments) is common across administrative workflows.

Laravel provides the expressive `doesntHave()` and `whereDoesntHave()` query builder methods.

## Finding Models with Zero Relations

```php
use App\Models\User;

// Finds users who have 0 orders
$inactiveUsers = User::doesntHave('orders')->get();

// Finds posts with 0 comments
$uncommentedPosts = Post::doesntHave('comments')->paginate(15);
```

## Conditional Filtering with whereDoesntHave()

To find records where a child relation does NOT match specific conditions:

```php
// Finds users who have NO orders in the last 6 months
$dormantUsers = User::whereDoesntHave('orders', function ($query) {
    $query->where('created_at', '>=', now()->subMonths(6));
})->get();
```

## Summary

- `doesntHave('relation')` compiles to an efficient SQL `WHERE NOT EXISTS` query.
- `whereDoesntHave('relation', fn ($q) => ...)` excludes models based on related column conditions.
- Ideal for finding inactive accounts and orphaned records.
