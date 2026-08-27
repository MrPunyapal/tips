---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance"]
date: "2023-05-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Check Relationship Presence Efficiently with withExists()

> Use withExists() in Eloquent queries to inject boolean relationship presence flags directly into model attributes via SQL subqueries.

When displaying lists of records and checking if a child relationship exists (such as checking if a user has active subscriptions, or if a post has comments), loading full relations or calling `$post->comments()->exists()` inside a loop causes N+1 queries.

`withExists()` queries presence in SQL and injects a boolean attribute (`relation_exists`).

## Querying Relationship Presence

```php
use App\Models\User;

$users = User::withExists([
    // Injects $user->posts_exists (true / false)
    'posts',

    // Filtered existence subquery: $user->active_subscription_exists
    'subscriptions as active_subscription_exists' => function ($query) {
        $query->where('is_active', true);
    }
])->paginate(20);
```

## In Blade Views

```blade
@foreach ($users as $user)
    <div>
        <h3>{{ $user->name }}</h3>
        @if ($user->active_subscription_exists)
            <span class="badge badge-success">Active Subscriber</span>
        @endif
    </div>
@endforeach
```

## Summary

- Executes a fast SQL `EXISTS(SELECT 1 FROM ...)` subquery.
- Adds boolean attributes (`true` / `false`) to hydrated models.
- Avoids loading full relationship models into memory just to check presence.
