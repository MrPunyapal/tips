---
category: "Laravel"
tags: ["Laravel", "Routing", "REST", "Clean Code"]
date: "2023-10-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Register 1:1 Resources with Route::singleton()

> Use Route::singleton() to register RESTful routes for resources that have only a single instance per user (like /profile or /settings).

Standard `Route::resource()` registers plural routes with identifier parameters (e.g. `/users/{user}`). For singleton resources where only one record exists for the authenticated user (such as `/profile` or `/settings`), passing IDs in the URL is unnecessary.

`Route::singleton()` registers dedicated singular routes without ID parameters.

## Registering a Singleton Resource

```php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Registers: GET /profile, GET /profile/edit, PUT /profile
Route::singleton('profile', ProfileController::class);
```

## Generated Routes

| Verb | URI | Action | Route Name |
|---|---|---|---|
| GET | `/profile` | show | profile.show |
| GET | `/profile/edit` | edit | profile.edit |
| PUT/PATCH | `/profile` | update | profile.update |

## Nested Singletons

Singletons can also be nested under parent resources:

```php
// Registers: /photos/{photo}/thumbnail
Route::resource('photos', PhotoController::class);
Route::singleton('photos.thumbnail', ThumbnailController::class);
```

## Summary

- Eliminates redundant `{id}` parameters for single-instance resources.
- Maps to standard `show`, `edit`, and `update` controller methods.
- Supports nested resource relationships.
