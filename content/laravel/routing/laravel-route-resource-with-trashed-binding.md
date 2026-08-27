---
category: "Laravel"
tags: ["Laravel", "Routing", "Eloquent", "Clean Code"]
date: "2023-07-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Enable Soft-Deleted Model Binding on Resource Routes with withTrashed()

> Chain withTrashed() onto Route::resource() or individual route definitions to automatically resolve soft-deleted models in route model binding.

By default, Laravel's route model binding excludes soft-deleted records, returning an immediate 404 response if a user requests an archived record.

When building admin panels or trash-recovery interfaces, you need route model binding to resolve soft-deleted instances.

## Enabling withTrashed on Resource Routes

Pass specific method names to `withTrashed()` on a resource route definition:

```php
use App\Http\Controllers\AdminPostController;
use Illuminate\Support\Facades\Route;

// Automatically binds soft-deleted posts on show, restore, and forceDelete routes
Route::resource('posts', PostController::class)
    ->withTrashed(['show', 'destroy']);
```

## Usage on Individual Routes

You can also attach `withTrashed()` directly to standalone route definitions:

```php
use App\Models\Post;

Route::post('/posts/{post}/restore', function (Post $post) {
    $post->restore();

    return redirect()->route('admin.posts.index')->with('success', 'Post restored.');
})->withTrashed();
```

## Controller Method Signature

The controller action receives the resolved `Post` instance directly, whether it is active or soft-deleted:

```php
public function show(Post $post): View
{
    // $post is resolved even if deleted_at is not null
    return view('admin.posts.show', compact('post'));
}
```

## Summary

- Enables route model binding to resolve models with `deleted_at IS NOT NULL`.
- Supports targeting specific resource controller actions via `->withTrashed(['show', 'update'])`.
- Eliminates manual `Post::withTrashed()->findOrFail($id)` queries in controller actions.
