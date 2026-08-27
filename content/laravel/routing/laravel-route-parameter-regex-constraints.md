---
category: "Laravel"
tags: ["Laravel", "Routing", "Regex", "Security"]
date: "2023-02-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Constrain Route Parameters with Regex and Helper Methods

> Restrict route parameter formats using where(), whereNumber(), whereAlpha(), and whereUuid() to prevent invalid route matches.

By default, Laravel route parameters accept any character sequence. If you have overlapping routes like `/users/{id}` (numeric ID) and `/users/{username}` (alphanumeric string), unconstrained parameters will cause routes to intercept each other unexpectedly.

Laravel provides parameter constraint methods to enforce regular expression boundaries directly in route definitions.

## Built-In Convenience Constraints

```php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Matches only numeric IDs (e.g. /users/42)
Route::get('/users/{id}', [UserController::class, 'showById'])
    ->whereNumber('id');

// Matches only alphanumeric slugs (e.g. /users/alex)
Route::get('/users/{username}', [UserController::class, 'showByUsername'])
    ->whereAlpha('username');

// Matches valid UUIDs
Route::get('/orders/{order:uuid}', [OrderController::class, 'show'])
    ->whereUuid('order');

// Matches valid ULIDs
Route::get('/invoices/{ulid}', [InvoiceController::class, 'show'])
    ->whereUlid('ulid');
```

## Custom Regex with where()

Pass custom regular expressions to `where()`:

```php
// Restricts category slug to lowercase letters and hyphens
Route::get('/categories/{slug}', [CategoryController::class, 'show'])
    ->where('slug', '[a-z0-9-]+');

// Restricts locale to 2-letter language codes
Route::get('/{locale}/about', [AboutController::class, 'show'])
    ->whereIn('locale', ['en', 'es', 'fr', 'de']);
```

## Global Route Patterns

To enforce a constraint globally across all routes, define it in `AppServiceProvider::boot()`:

```php
Route::pattern('id', '[0-9]+');
```

## Summary

- Prevents overlapping route collisions between numeric IDs and string slugs.
- Automatically throws HTTP 404 if parameter constraints fail before reaching controller logic.
- Built-in helpers: `whereNumber()`, `whereAlpha()`, `whereAlphaNumeric()`, `whereUuid()`, `whereUlid()`, `whereIn()`.
