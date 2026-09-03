---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2024-03-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Clear Query Order Constraints with reorder()

> Use reorder() to clear previous orderBy clauses from a query builder before applying new sorting constraints.

When working with base query scopes or repository methods that already include an `orderBy` clause, calling `orderBy()` again appends a secondary sort rather than replacing the primary sort.

`reorder()` removes previously assigned order clauses cleanly.

---

## Code Examples

```php
use App\Models\User;

// Base query has default alphabetical ordering
$query = User::orderBy('name', 'asc');

// 1. Completely remove all ORDER BY clauses (runs without order constraints)
$unordered = (clone $query)->reorder()->get();

// 2. Clear previous sort and apply new ordering
$recentUsers = $query->reorder('created_at', 'desc')->get();
// SQL: select * from `users` order by `created_at` desc
```

---

## Key Benefits

- **Scope Overrides**: Safely override hardcoded sorting rules defined in model global or local query scopes.
- **Clean Reset**: Calling `->reorder()` without arguments resets the query's internal `$orders` array to empty.
- **Direction Flexibility**: Accepts new column and direction arguments (`'asc'` or `'desc'`) in a single fluent call.
