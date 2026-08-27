---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Polymorphism", "Database"]
date: "2025-02-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Query Polymorphic Relations Expressively with whereMorphedTo()

> Use whereMorphedTo() to query polymorphic models without manually checking both type and ID columns.

When querying polymorphic relationships (such as comments that belong to either a `Post`, `Video`, or `Lesson`), writing `where('commentable_type', Post::class)->where('commentable_id', $post->id)` is verbose.

`whereMorphedTo()` queries polymorphic targets directly from model instances.

## Basic Usage

```php
use App\Models\Comment;
use App\Models\Post;

$post = Post::find(1);

// Generates: WHERE commentable_type = 'App\Models\Post' AND commentable_id = 1
$comments = Comment::whereMorphedTo('commentable', $post)->get();
```

## Querying Against Model Collections

You can also pass a collection of models to query across multiple polymorphic parents:

```php
$posts = Post::where('featured', true)->get();

// Generates WHERE (commentable_type = 'Post' AND commentable_id IN (1, 2, 3))
$featuredComments = Comment::whereMorphedTo('commentable', $posts)->get();
```

## Summary

- Automatically infers the polymorphic type and primary key from model instances.
- Replaces manual `where('..._type', ...)->where('..._id', ...)` chains.
- Supports single models and Eloquent collections.
