---
category: "Laravel"
tags: ["Laravel", "Routing", "Eloquent", "Clean Code"]
date: "2023-07-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Customize Route Model Binding Resolution Columns in Route Definitions

> Specify custom database lookup columns directly in route parameters (like {post:slug}) without overriding getRouteKeyName() on the model.

By default, Laravel's implicit route model binding queries records by their primary key (`id`). When querying models by unique columns (such as `slug`, `username`, or `uuid`), you can declare the target column inline directly in your route definition.

## Inline Custom Binding Column

Append a colon and the column name to the route parameter:

```php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Resolves Post using: WHERE slug = ?
Route::get('/posts/{post:slug}', [PostController::class, 'show']);

// Resolves User using: WHERE username = ?
Route::get('/users/{user:username}', [UserController::class, 'show']);
```

## Scoped Nested Route Model Binding

When nesting resources, Laravel automatically scopes child bindings to the parent model:

```php
// Automatically scopes: Post where user_id = $user->id AND slug = $slug
Route::get('/users/{user}/posts/{post:slug}', [PostController::class, 'show']);
```

If the post does not belong to the resolved user, Laravel automatically returns a 404 response.

## Summary

- Eliminates the need to override `getRouteKeyName()` globally on the model.
- Allows different routes to resolve the same model using different columns (e.g. `id` in admin routes, `slug` in public URLs).
- Automatically enforces relational scoping on nested resource routes.
