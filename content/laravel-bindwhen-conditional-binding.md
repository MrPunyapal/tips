---
category: "Laravel"
tags: ["Laravel", "Service Container", "Attributes"]
date: "2026-07-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Conditional Dependency Binding with #[BindWhen] in Laravel 13.22

> Laravel 13.22 introduces the #[BindWhen] attribute for declarative, conditional service container bindings directly on implementation classes.

Instead of cluttering AppServiceProvider with conditional bind logic, use the #[BindWhen] attribute directly on implementation classes. The closure receives the container instance to resolve runtime conditions.

```php
use Illuminate\Container\Attributes\BindWhen;
use Illuminate\Contracts\Container\Container;

#[BindWhen(
    PaymentGateway::class,
    fn (Container $app) => $app->make('config')->get('services.gateway') === 'stripe'
)]
class StripeGateway implements PaymentGateway {}

```

- Closure receives Container instance for runtime decisions
- Can be repeated: declaration order determines priority
- Requires PHP 8.5 for closure-in-attribute support
