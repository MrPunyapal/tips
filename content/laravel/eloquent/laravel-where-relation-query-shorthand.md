---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Queries"]
date: "2023-05-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Simplify Relationship Queries with whereRelation()

> Replace verbose whereHas() closures with whereRelation() and orWhereRelation() for clean single-column relationship queries.

Querying a model based on constraints on a related table usually requires wrapping `whereHas()` inside a closure:

```php
// Traditional verbose syntax
$users = User::whereHas('posts', function ($query) {
    $query->where('status', 'published');
})->get();
```

For single-column checks, Laravel provides the `whereRelation()` shorthand.

## Using whereRelation()

Pass the relationship name, column name, and value directly:

```php
use App\Models\User;

// Concise whereRelation syntax
$users = User::whereRelation('posts', 'status', 'published')->get();
```

## Comparison Operators

You can also pass comparison operators as the third argument:

```php
use App\Models\Company;

// Find companies where subscription price is greater than 100
$companies = Company::whereRelation('subscription', 'price', '>', 100)->get();
```

## Nested Dot Notation Relationships

`whereRelation()` also supports nested relationships using dot notation:

```php
// Find authors whose posts have comments flagged as spam
$authors = User::whereRelation('posts.comments', 'is_spam', true)->get();
```

## Summary

- Replaces verbose `whereHas('relation', fn ($q) => $q->where(...))` closures with a single line.
- Supports standard comparison operators (`=`, `>`, `<`, `LIKE`).
- Works directly with nested relationships via dot notation (`relation.nestedRelation`).
