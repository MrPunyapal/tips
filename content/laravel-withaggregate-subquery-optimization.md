---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance", "Database"]
date: "2026-06-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Fetch Specific Related Attributes with withAggregate()

> Use `withAggregate()` to pull a single column value from a relationship via a SQL subquery without eager loading full model instances into memory.

When you need a single attribute or calculation from a related model (like a user's name or the latest comment date), eager loading the entire relationship (`with('user')`) wastes memory and CPU cycles.

Laravel's `withAggregate()` runs a subselect query directly in the database.

### Fetching a Related Column

```php
use App\Models\Post;

// Pulls user's name directly as $post->user_name via SQL subquery
$posts = Post::withAggregate('user', 'name')->get();

foreach ($posts as $post) {
    echo $post->user_name;
}
```

### Custom Aggregates with Aliasing

```php
// Gets the latest comment date as $post->latest_comment_at
$posts = Post::withAggregate('comments as latest_comment_at', 'created_at', 'max')->get();

foreach ($posts as $post) {
    echo $post->latest_comment_at;
}
```

- `withAggregate()` creates virtual attributes named `{relation}_{column}` or your explicit alias
- Runs in a single database subquery instead of executing N+1 queries
- Standard helpers `withCount()`, `withSum()`, and `withAvg()` use `withAggregate()` under the hood
