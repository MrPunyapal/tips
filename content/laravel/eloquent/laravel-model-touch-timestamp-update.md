---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2025-06-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Use $model->touch() Instead of Manual Timestamp Updates

> Use the built-in touch() method to update updated_at or custom timestamp columns on Eloquent records cleanly.

Manually writing `$model->updated_at = now(); $model->save();` is repetitive and clutters model workflows.

Calling `$model->touch()` updates the timestamp and saves the record in a single operation.

---

## Standard & Custom Column Usage

```php
use App\Models\Post;

$post = Post::findOrFail($id);

// 1. Update the default 'updated_at' timestamp
$post->touch();

// 2. Update a custom timestamp column
$post->touch('last_viewed_at');
```

---

## Cascading to Parent Relationships

To touch parent models whenever a child is updated, define the `$touches` property on the child model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Automatically touch parent Post's updated_at when a comment changes
    protected $touches = ['post'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
```

---

## Key Points

- **Atomic Update**: Sets the timestamp to current time and saves the model immediately.
- **Custom Columns**: Pass any datetime column name as the first argument to update custom tracking fields.
- **Cache Invalidation**: Triggers parent timestamp updates configured via `$touches`, useful for cache busting parent models.
