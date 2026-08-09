---
category: "Laravel"
tags: ["Laravel", "Service Container", "Attributes", "PHP 8.5"]
date: "2026-07-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Conditional Dependency Binding with #[BindWhen] in Laravel 13.22

> Laravel 13.22 introduces the `#[BindWhen]` attribute for declarative, conditional service container bindings. The closure receives the container, multiple attributes can be stacked, and declaration order matters. Requires PHP 8.5.

Instead of cluttering your AppServiceProvider with conditional bind logic, you can now use the `#[BindWhen]` attribute directly on the implementation class. 

### Conditional Binding

```php
use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\BindWhen;
use Illuminate\Contracts\Container\Container;

#[BindWhen(
    PaymentGateway::class,
    fn (Container $app) => $app->make('config')->get('services.gateway') === 'stripe'
)]
class StripeGateway implements PaymentGateway
{
    // Bound only when config says 'stripe'
}
```

### Stacking with Fallback

```php
#[BindWhen(PaymentGateway::class, fn (Container $app) => $app->make('config')->get('services.gateway') === 'stripe')]
#[Bind(PaymentGateway::class)] // Default fallback
class StripeGateway implements PaymentGateway {}
```

- Closure receives the Container instance for runtime decisions
- Can be repeated: declaration order determines priority
- Place a default `#[Bind]` after `#[BindWhen]` as a fallback
- Requires PHP 8.5 for closure-in-attribute support
