---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance", "Clean Code"]
date: "2023-09-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Eager Load Relationships Conditionally with loadMissing()

> Use loadMissing() to eager-load relationships only if they have not already been loaded on the model instance.

When passing models through service layers, notification handlers, or Blade components, calling `$post->load('author')` forces a new database query even if `author` was already eager-loaded upstream.

The `loadMissing()` method checks if the relationship is already loaded before executing any query.

## Basic Usage

```php
use App\Models\Post;

public function formatPost(Post $post): array
{
    // Executes query ONLY IF 'author' and 'comments' are not already loaded
    $post->loadMissing(['author', 'comments.user']);

    return [
        'title'  => $post->title,
        'author' => $post->author->name,
        'count'  => $post->comments->count(),
    ];
}
```

## Difference Between load() and loadMissing()

- **`$model->load('relation')`**: Always executes a new database query to reload the relation.
- **`$model->loadMissing('relation')`**: Checks `$model->relationLoaded('relation')` first, querying only if missing.

## Summary

- Prevents duplicate database queries across multi-layered services.
- Supports nested relationships using dot notation (`comments.user`).
- Ideal for helper functions and formatting pipelines.
