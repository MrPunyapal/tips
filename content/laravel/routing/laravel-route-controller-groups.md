---
category: "Laravel"
tags: ["Laravel", "Routing", "Controllers", "Clean Code"]
date: "2023-01-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Group Route Definitions by Controller with Route::controller()

> Use Route::controller() to define multiple routes for a single controller without repeating the controller class name on every route definition.

When building non-resource endpoints or administrative workflows, writing `[OrderController::class, 'method']` across a dozen route definitions creates unnecessary visual clutter.

`Route::controller()` defines a shared controller context for an entire group of routes.

## Defining Controller Groups

```php
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)->group(function () {
    Route::get('/orders', 'index')->name('orders.index');
    Route::post('/orders', 'store')->name('orders.store');
    Route::get('/orders/{order}', 'show')->name('orders.show');
    Route::post('/orders/{order}/refund', 'refund')->name('orders.refund');
    Route::post('/orders/{order}/cancel', 'cancel')->name('orders.cancel');
});
```

## Combining with Prefix and Middleware Groups

```php
Route::prefix('admin')
    ->middleware(['auth', 'verified'])
    ->controller(AdminDashboardController::class)
    ->group(function () {
        Route::get('/metrics', 'metrics');
        Route::get('/health', 'health');
        Route::post('/cache/clear', 'clearCache');
    });
```

## Summary

- Reduces repetition of controller class names across route files.
- Makes non-standard and action-oriented route groups clean and readable.
- Fully compatible with route prefixes, names, and middleware groups.
