---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance"]
date: "2026-06-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Fetch Specific Related Attributes with withAggregate()

> Use withAggregate() to pull a single column value from a relationship via a SQL subquery without eager loading full model instances.

When you need a single attribute from a related model, eager loading the entire relationship into memory wastes memory and hydration overhead.

Laravel's `withAggregate()` runs a correlated subquery directly in the primary SQL statement, attaching the result as a virtual attribute on each parent model.

---

## Code Examples

```php
use App\Models\Post;

// 1. Pull a single related column (creates $post->user_name)
$posts = Post::withAggregate('user', 'name')->get();

// 2. Custom alias using the 'relation as alias' syntax
$posts = Post::withAggregate('user as author_email', 'email')->get();
// Accessible as: $post->author_email

// 3. Aggregate functions (MAX, MIN, AVG, SUM) with custom alias
$posts = Post::withAggregate('comments as latest_comment_at', 'created_at', 'max')->get();
// Accessible as: $post->latest_comment_at
```

---

## Key Benefits

- **Method Signature**: Accepts `($relations, $column, $function = null)`. Aliases must be specified in the relation argument using `'relation as alias'`.
- **Zero Hydration Overhead**: Attaches single column values without instantiating full Eloquent model objects for related rows.
- **Underlying Engine**: Serves as the flexible foundation behind `withCount()`, `withMax()`, and `withAvg()`.
- **Direct SQL Sorting**: You can order by the virtual attribute directly in SQL: `->orderBy('latest_comment_at', 'desc')`.
