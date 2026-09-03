---
category: "Laravel"
tags: ["Laravel", "Deployment", "Artisan", "Performance"]
date: "2025-02-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Hook Custom Cache Generation into php artisan optimize with App::optimizes()

> Use App::optimizes() in Service Providers to register custom cache generation commands that automatically run during php artisan optimize.

When building packages or domain systems that generate custom caches (such as compiling dynamic permission matrices, building search indexes, or caching OpenAPI schemas), developers often have to remember separate custom CLI commands during deployment.

Laravel allows service providers to register tasks into `php artisan optimize` and `php artisan optimize:clear`.

## Registering Optimization Hooks in AppServiceProvider

```php
namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Automatically runs during 'php artisan optimize' and clears during 'optimize:clear'
        App::optimizes(
            optimize: 'permissions:cache',
            clear: 'permissions:clear',
            key: 'permissions'
        );
    }
}
```

## Deploying

Now running `php artisan optimize` in your CI/CD pipeline automatically compiles your custom caches alongside route, config, and view caches.

## Summary

- Integrates custom caching commands into standard Laravel deployment lifecycles.
- Automatically handles cache clearing with `php artisan optimize:clear`.
- Keeps deployment scripts clean and unified.
