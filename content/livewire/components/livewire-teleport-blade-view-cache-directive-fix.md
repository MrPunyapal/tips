---
category: "Livewire"
tags: ["Laravel", "Livewire", "Blade", "Alpine.js", "Tooling"]
date: "2024-01-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Components"
---

# Fix Blade View Cache Compilation for Teleport Directives in Livewire

> Prevent view compilation glitches when running php artisan view:cache on templates using @teleport by registering explicit Blade directives.

When using Livewire or Alpine.js `@teleport` directives inside Blade templates, running `php artisan view:cache` in production or CI/CD pipelines can occasionally cause compilation errors or unparsed tags if custom directive compilation is missing from the service container.

You can ensure consistent, error-free view compilation by registering custom Blade compiler directives for `@teleport` and `@endteleport`.

## The Fix in AppServiceProvider

Add the custom directive registration inside your `AppServiceProvider::register()` or `boot()` method:

```php
namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('teleport', function (string $expression): string {
            return "<template x-teleport="<?php echo e({$expression}); ?>">";
        });

        Blade::directive('endteleport', function (): string {
            return "</template>";
        });
    }
}
```

## Usage in Blade Views

You can now use `@teleport` cleanly across cached views:

```blade
<div x-data="{ open: false }">
    <button @click="open = true">Open Modal</button>

    @teleport('body')
        <div x-show="open" class="modal-backdrop">
            <div class="modal-content">
                <h3>Modal Title</h3>
                <button @click="open = false">Close</button>
            </div>
        </div>
    @endteleport
</div>
```

## Testing View Caching

Run view cache compilation to verify:

```bash
php artisan view:clear
php artisan view:cache
```

All views compile cleanly into cached PHP templates without syntax exceptions.

## Summary

- Register explicit `@teleport` and `@endteleport` Blade directives in `AppServiceProvider` to resolve compilation glitches during `view:cache`.
- Translates cleanly into standard Alpine.js `<template x-teleport="...">` elements.
- Prevents production deployment build failures when caching views.
