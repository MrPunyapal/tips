---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Relationships"]
date: "2026-03-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Remove Global Eager Loading with withoutRelation()

> Use withoutRelation() or unsetRelation() to remove eager loaded relationships on specific Eloquent queries or model instances.

Models with $with properties eager load relations globally on every query. Use withoutRelation() on query builders to bypass global eager loads when relations are unneeded.

```php
use App\Models\Post;

// Model defines protected $with = ['author', 'comments'];

// Query: Exclude 'comments' relation for this specific query
$posts = Post::withoutRelation('comments')->get();

// Instance: Unset loaded relation on an existing model object
$post->unsetRelation('comments');
```

- Bypasses default $with eager loading definitions per-query
- unsetRelation() clears in-memory relation data on model instances
- Reduces unnecessary database subqueries for specific lightweight queries
