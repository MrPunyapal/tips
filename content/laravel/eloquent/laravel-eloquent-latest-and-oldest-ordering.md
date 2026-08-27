---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2025-10-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Simplify Chronological Query Sorting with latest() and oldest()

> Replace verbose orderBy('created_at', 'desc') clauses with the clean latest() and oldest() query builder helpers.

Sorting records by creation timestamp is the most common ordering operation in database queries.

Instead of writing `orderBy('created_at', 'desc')` or `orderBy('created_at', 'asc')`, Laravel provides the `latest()` and `oldest()` query builder methods.

## Basic Usage

```php
use App\Models\Post;

// Equivalent to: orderBy('created_at', 'desc')
$newestPosts = Post::latest()->paginate(15);

// Equivalent to: orderBy('created_at', 'asc')
$oldestPosts = Post::oldest()->paginate(15);
```

## Ordering by Custom Date Columns

Pass a column name to sort chronologically by custom date or integer columns:

```php
// Sort by published_at DESC
$recentArticles = Article::latest('published_at')->get();

// Sort by scheduled_for ASC
$upcomingEvents = Event::oldest('scheduled_for')->get();
```

## Summary

- Defaults to the model's `created_at` timestamp column.
- Accepts custom column names as the first argument.
- Clean and self-documenting builder syntax.
