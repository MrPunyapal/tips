---
category: "Laravel"
tags: ["Laravel", "Authorization", "Routing", "Security"]
date: "2023-12-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Authorize Abilities Directly on Routes with the can: Middleware

> Use Laravel's built-in can: middleware to enforce policy authorization checks directly on route declarations.

Instead of calling `$this->authorize('update', $post)` inside every controller action, you can attach authorization checks directly to route definitions using the `can:` middleware.

## Authorizing Model Actions on Routes

Pass the ability name and the route parameter name to `can:`:

```php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Checks PostPolicy::update($user, $post) before invoking the controller
Route::put('/posts/{post}', [PostController::class, 'update'])
    ->middleware('can:update,post');

// Checks PostPolicy::delete($user, $post)
Route::delete('/posts/{post}', [PostController::class, 'destroy'])
    ->middleware('can:delete,post');
```

## Authorizing Actions Without Model Instances

When checking abilities that do not require an existing model instance (such as creating new records):

```php
use App\Models\Post;

// Checks PostPolicy::create($user)
Route::post('/posts', [PostController::class, 'store'])
    ->middleware('can:create,' . Post::class);
```

## Custom Error Handling

If authorization fails, Laravel automatically throws an `AuthorizationException` (HTTP 403 Forbidden).

## Summary

- Enforces authorization at the HTTP routing boundary before controller code runs.
- Resolves implicit route model bindings automatically for policy checks.
- Keeps controller methods focused on request handling and domain logic.
