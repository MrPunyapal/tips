---
category: "Laravel"
subcategory: "Eloquent"
tags: ["Laravel", "Eloquent", "Performance", "Relationships"]
date: "2026-10-19"
author: "COCOUVI Alexandro K.A"
author_url: "https://x.com/XTech26551"
---
# Fix the Inverse N+1 Problem with chaperone()

> Using `with()` but still getting N+1 queries? Eloquent doesn't automatically hydrate the parent on each child. Fix it with `->chaperone()` on the relationship.

Even with `Post::with('comments')`, accessing `$comment->post` inside a loop lazy-loads the parent **for every comment**. This is the inverse side of the N+1 problem: eager loading doesn't cover it, so you get one silent query per comment.

## Without chaperone()

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
```

```php
$posts = Post::with('comments')->get();

foreach ($posts as $post) {
    foreach ($post->comments as $comment) {
        echo $comment->post->title;
    }
}

// 💥 1 query for posts + 1 for comments + 1 query PER comment
```

## With chaperone()

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->chaperone(); // 👈 This line fixes the inverse N+1
    }
}
```

```php
$posts = Post::with('comments')->get();

foreach ($posts as $post) {
    foreach ($post->comments as $comment) {
        echo $comment->post->title; // ✅ No extra query per comment
    }
}
```

`chaperone()` sets the parent instance that is already loaded onto each child, so accessing `$comment->post` doesn't hit the database.

You can also opt in per query instead of defining it on the relationship:

```php
$posts = Post::with([
    'comments' => fn ($comments) => $comments->chaperone(),
])->get();
```

If the inverse relationship on the child has a different name, pass it explicitly:

```php
return $this->hasMany(Comment::class)->chaperone('post');
```

## Summary

- Fixes the inverse side of N+1: children reuse their already loaded parent, no extra query.
- Available on `HasOne`, `HasMany`, `MorphOne` and `MorphMany` relationships (Laravel 11.22+).
- `inverse()` is an alias of `chaperone()`.
- Nothing changes at the call site: keep using `Post::with('comments')`.
