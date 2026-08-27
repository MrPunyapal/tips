---
category: "Laravel"
tags: ["Laravel", "HTTP", "API", "Controllers"]
date: "2026-02-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Handle Hybrid API and Web Controllers with $request->expectsJson()

> Use $request->expectsJson() in hybrid controllers to serve formatted JSON responses to API/AJAX requests and redirects to standard web browsers.

When building controllers shared between traditional Blade views and AJAX/Inertia/API clients (such as login, search, or deletion endpoints), branching based on client request headers is standard practice.

`$request->expectsJson()` inspects `Accept` and `X-Requested-With` headers automatically.

## Hybrid Controller Action

```php
use App\Models\Post;
use Illuminate\Http\Request;

public function destroy(Request $request, Post $post)
{
    $post->delete();

    // If client requested JSON (API / AJAX / Mobile app)
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ]);
    }

    // Standard web browser form submission
    return to_route('posts.index')
        ->with('success', 'Post deleted.');
}
```

## What expectsJson() Inspects

- Checks if the `Accept` header contains `application/json`.
- Checks if the request is an AJAX call via `X-Requested-With: XMLHttpRequest`.

## Summary

- Reusable pattern for multi-channel hybrid controllers.
- Eliminates duplicate controller actions for web and API endpoints.
- Built-in to all Laravel request instances.
