---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance"]
date: "2026-06-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Combine Filter and Eager Loading with withWhereHas()

> Use withWhereHas() to filter records based on relationship conditions and eager load the filtered relationship in a single method.

Filtering models by relationship criteria while also eager loading the relationship previously required duplicating closures across whereHas() and with(). withWhereHas() performs both tasks in one call.

```php
use App\Models\User;

// BEFORE: Duplicated relationship closure constraint
$users = User::whereHas('posts', fn ($q) => $q->where('published', true))
    ->with(['posts' => fn ($q) => $q->where('published', true)])
    ->get();

// AFTER: Combines filtering and eager loading cleanly
$users = User::withWhereHas('posts', fn ($q) => $q->where('published', true))->get();
```

- Eliminates duplicate constraint closures across whereHas and with
- Filters parent models while simultaneously eager loading matching children
- Keeps Eloquent builder queries concise and maintainable
