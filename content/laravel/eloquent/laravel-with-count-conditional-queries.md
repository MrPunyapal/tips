---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance"]
date: "2023-06-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Calculate Conditional Relationship Totals with withCount()

> Use withCount() with nested closures and aliases to count specific subsets of child relationships directly in SQL.

When displaying counts of related records (such as counting only published posts or pending orders), loading full relationships and calling `$user->posts->where('status', 'published')->count()` loads all model instances into memory.

`withCount()` counts records directly inside the database query.

## Counting Conditional Subsets

```php
use App\Models\User;

$users = User::withCount([
    // Total count: $user->posts_count
    'posts',

    // Filtered count with custom alias: $user->published_posts_count
    'posts as published_posts_count' => function ($query) {
        $query->where('status', 'published');
    },

    // Filtered count: $user->pending_orders_count
    'orders as pending_orders_count' => function ($query) {
        $query->where('status', 'pending');
    }
])->paginate(20);
```

## Accessing Counts in Views

The counts are injected as virtual model attributes:

```blade
@foreach ($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->published_posts_count }} / {{ $user->posts_count }}</td>
    </tr>
@endforeach
```

## Summary

- Executes aggregate SQL subqueries (`SELECT count(*) FROM ...`) in a single query.
- Uses `'relation as alias_count' => fn ($q) => ...` to define multiple filtered metrics.
- Eliminates memory exhaustion caused by loading models solely for counting.
