---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance"]
date: "2026-06-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Fetch Specific Related Attributes with withAggregate()

> Use withAggregate() to pull a single column value from a relationship via a SQL subquery without eager loading full model instances.

When you need a single attribute from a related model, eager loading the entire relationship wastes memory. Laravel's withAggregate() runs a subselect query directly in SQL.

```php
use App\Models\Post;

// Pulls user name directly as $post->user_name via SQL subquery
$posts = Post::withAggregate('user', 'name')->get();
```

- Creates virtual attributes named {relation}_{column} or your alias
- Runs in a single database subquery instead of N+1 queries
- Underlies withCount(), withSum(), and withAvg() helpers
