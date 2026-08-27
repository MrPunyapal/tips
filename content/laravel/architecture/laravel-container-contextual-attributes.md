---
category: "Laravel"
tags: ["Laravel", "Container", "Attributes", "PHP 8.2+"]
date: "2025-01-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Inject Configuration and Models Directly with Contextual Attributes

> Use #[Config], #[CurrentUser], and #[RouteParameter] attributes to inject configuration and contextual parameters directly into service constructors.

Traditionally, injecting primitive values (like API keys or base URLs) into service classes required writing manual `$this->app->when(...)->needs(...)->give(...)` bindings in `AppServiceProvider`.

Modern Laravel supports contextual dependency injection attributes directly on constructor parameters.

## Injecting Configuration with #[Config]

```php
namespace App\Services;

use Illuminate\Container\Attributes\Config;

class StripePaymentGateway
{
    public function __construct(
        #[Config('services.stripe.secret')]
        protected string $secretKey,

        #[Config('services.stripe.currency', 'usd')]
        protected string $currency
    ) {}
}
```

## Injecting the Authenticated User with #[CurrentUser]

```php
namespace App\Services;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;

class UserAuditLogger
{
    public function __construct(
        #[CurrentUser]
        protected ?User $actor
    ) {}
}
```

## Summary

- Eliminates verbose contextual bindings in `AppServiceProvider`.
- Works on controller actions, service classes, and job constructors.
- Native PHP attributes: `#[Config]`, `#[CurrentUser]`, `#[RouteParameter]`, `#[Storage]`.
