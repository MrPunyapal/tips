---
category: "Laravel"
tags: ["Laravel", "Configuration", "Testing", "Pest PHP"]
date: "2023-11-30"
updated: "2026-08-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Configuration"
---

# Use config() Instead of env() in Application Code

> Call env() only inside configuration files and use config() throughout your application code to prevent missing values when configuration is cached.

A foundational Laravel convention is keeping `env()` calls confined strictly to files in the `config/` directory while reading settings across controllers, services, and models via `config()`.

## The Common Mistake

It is tempting to read environment variables directly inside application code:

```php
// ❌ Don't call env() directly in application code
$stripeKey = env('STRIPE_KEY');
```

While this works during local development when `.env` is loaded on every request, it breaks in production when configuration caching is enabled.

## The Laravel Way

Environment variables should be accessed inside configuration files, and application code should read from Laravel's configuration repository:

```php
// config/services.php
return [
    'stripe' => [
        'key' => env('STRIPE_KEY'),
    ],
];
```

Application code then retrieves the setting through `config()`:

```php
// ✅ Read the configured value
$stripeKey = config('services.stripe.key');
```

The application does not need to know where the value originated; it accesses the value through Laravel's unified configuration repository.

## Why config:cache Matters

In production, optimizing performance involves running:

```bash
php artisan config:cache
```

Once configuration is cached, Laravel combines all files in `config/*.php` into a single cached file and ignores the `.env` file during requests and Artisan commands.

When `.env` is not loaded:
- `config('services.stripe.key')` reads the compiled value from the configuration cache.
- Calling `env('STRIPE_KEY')` directly in application code bypasses the configuration cache and returns `null`, unless an external operating system environment variable of the same name is explicitly defined on the host server.

## Enforce It with Pest

You can enforce this convention automatically using a Pest architecture test:

```php
// tests/ArchitectureTest.php
arch('globals')
    ->expect(['env'])
    ->not->toBeUsed();
```

This test flags any direct `env()` usage in your application code before code is merged or deployed.

## Catch It with Larastan

Larastan includes static analysis rules to detect `env()` usage outside configuration files.

The `larastan.noEnvCallsOutsideOfConfig` rule (enabled by default in Larastan 3.x) analyzes your codebase and reports violations:

```text
Called 'env' outside of the config directory which returns null when the config is cached, use 'config'.
```

## Quick Rule

```text
.env → env() → config/*.php → config() → application code
```

- `env()` belongs in `config/*.php` files.
- `config()` belongs everywhere else in application code.
