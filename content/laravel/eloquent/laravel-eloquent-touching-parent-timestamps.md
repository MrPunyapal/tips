---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Cache", "Relationships"]
date: "2023-08-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Invalidate Caches Automatically by Touching Parent Timestamps with $touches

> Define the $touches property on child models to update parent model updated_at timestamps automatically when child records change.

When caching parent model data or HTTP responses (such as caching a `Post` with its `Comments`), adding or updating a comment leaves the parent post's `updated_at` timestamp unchanged, resulting in stale cache hits.

Defining `$touches` on the child model updates parent timestamps automatically.

## Configuring $touches on Child Models

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    // List parent relationships to touch when this model is saved or deleted
    protected $touches = ['post'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
```

## How It Works

When a comment is created, updated, or deleted:

```php
$comment = Comment::find(1);
$comment->update(['content' => 'Updated comment text.']);

// The associated Post's updated_at timestamp is automatically set to now()!
```

## Summary

- Automatically refreshes parent `updated_at` timestamps on child save and delete events.
- Essential for HTTP `ETag` generation and timestamp-based cache invalidation.
- Supports multiple parent relationship names in the array (`['post', 'author']`).
