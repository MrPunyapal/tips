---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance", "Debugging"]
date: "2024-05-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Detect and Eliminate N+1 Queries in Development with preventLazyLoading()

> Enable Model::preventLazyLoading() in local development to automatically throw exceptions whenever a relationship is lazy loaded.

Accidentally accessing relationships inside loops (like `$user->posts` without `User::with('posts')`) causes silent N+1 query performance degradation that often goes unnoticed until production traffic spikes.

`Model::preventLazyLoading()` forces Eloquent to throw an exception when lazy loading occurs.

## Enabling in AppServiceProvider

```php
namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Throw exceptions locally; log warnings in production
        Model::preventLazyLoading(! $this->app->isProduction());

        // Handle violations in production gracefully
        if ($this->app->isProduction()) {
            Model::handleLazyLoadingViolationsUsing(function ($model, $relation) {
                logger()->warning("Lazy loading [{$relation}] on [{$model::class}] in production.");
            });
        }
    }
}
```

## What Happens When a Relation is Lazy Loaded

```text
Attempted to lazy load [comments] on model [AppModelsPost] but lazy loading is disabled.
```

## Summary

- Catches N+1 query performance bugs during development and test suites.
- Fails fast with descriptive stack traces pointing directly to the offending blade view or loop.
- Supports custom violation handlers for production telemetry.
