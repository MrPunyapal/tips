---
category: "Laravel"
tags: ["Laravel", "Architecture", "Design Patterns", "Clean Code"]
date: "2024-06-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Execute Multi-Step Business Workflows with the Pipeline Facade

> Use Laravel's Pipeline facade to pass objects through sequential pipe classes, keeping complex order processing and data transformations modular.

When an application workflow involves multiple distinct stages (such as validating payment, applying coupons, calculating tax, and generating invoices), stuffing all logic into a single service method creates monolithic, hard-to-test code.

Laravel's `Pipeline` pattern executes sequential pipes cleanly.

## Defining Pipe Classes

Each pipe receives the payload and a `$next` closure:

```php
namespace App\Pipelines\Orders;

use Closure;

class ApplyDiscountCoupon
{
    public function handle(Order $order, Closure $next)
    {
        if ($order->coupon_code) {
            $order->discount = CouponService::calculate($order);
        }

        return $next($order);
    }
}
```

## Executing the Pipeline

```php
use App\Pipelines\Orders\ApplyDiscountCoupon;
use App\Pipelines\Orders\CalculateTax;
use App\Pipelines\Orders\ProcessStripePayment;
use Illuminate\Support\Facades\Pipeline;

$processedOrder = Pipeline::send($order)
    ->through([
        ApplyDiscountCoupon::class,
        CalculateTax::class,
        ProcessStripePayment::class,
    ])
    ->then(fn ($order) => $order->finalize());
```

## Summary

- Deconstructs complex operations into focused, single-responsibility pipe classes.
- Enables easy reordering, adding, or removing of business steps.
- Uses standard middleware-style `handle($passable, Closure $next)` signatures.
