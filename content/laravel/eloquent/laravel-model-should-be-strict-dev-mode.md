---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance", "Debugging"]
date: "2023-02-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Catch N+1 Queries and Model Mistakes with Model::shouldBeStrict()

> Enable Model::shouldBeStrict() in development to automatically throw exceptions on lazy loading, unfillable attributes, and missing model properties.

In development environments, subtle bugs like lazy loading (N+1 query problems), mass-assigning unguarded attributes, or accessing misspelled model attributes can easily slip into production undetected.

Calling `Model::shouldBeStrict()` enables three strict development safeguards in a single call.

## Enabling Strict Mode

Add the check inside `AppServiceProvider::boot()`:

```php
namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Enable strict checks in local and testing environments
        Model::shouldBeStrict(! $this->app->isProduction());
    }
}
```

## What shouldBeStrict() Enforces

1. **Prevents Lazy Loading (`preventLazyLoading`)`**:
   Throws a `LazyLoadingViolationException` whenever a relationship is loaded outside an eager-loading query, eliminating N+1 performance bottlenecks.
2. **Prevents Silently Discarding Attributes (`preventSilentlyDiscardingAttributes`)`**:
   Throws a `MassAssignmentException` if you pass fields to `create()` or `update()` that are not defined in `$fillable`.
3. **Prevents Accessing Missing Attributes (`preventAccessingMissingAttributes`)`**:
   Throws a `MissingAttributeException` if you attempt to read an attribute that was excluded from a partial `select('id', 'name')` query.

## Summary

- Catches N+1 query regressions immediately during development and test suites.
- Prevents silent data loss caused by misconfigured `$fillable` arrays.
- Zero performance impact in production when conditioned on `! $this->app->isProduction()`.
