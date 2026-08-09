---
category: "Laravel"
tags: ["Laravel","Routing"]
date: "2023-06-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Customize Missing Model Binding Behavior in Laravel

> Use missing() callbacks on route model bindings to return custom JSON responses or specialized error pages when records are not found.

By default, implicit route model binding throws a 404 ModelNotFoundException when a record missing from the database is requested.

You can customize this missing behavior per route by attaching the `->missing()` closure:

```php
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->missing(function (Request $request) {
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Post no longer exists'], 404);
        }

        return redirect()->route('posts.index')
            ->with('warning', 'Requested post was not found.');
    });
```

- Customizes 404 fallback logic per individual route or resource
- Prevents raw 404 exception screens for friendly user redirects
- Works seamlessly with custom route binding columns like `{post:slug}`
