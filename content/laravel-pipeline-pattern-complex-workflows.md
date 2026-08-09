---
category: "Laravel"
tags: ["Laravel", "Architecture", "Design Patterns"]
date: "2026-04-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Clean Up Complex Multi-Step Operations with Illuminate Pipeline

> Process complex data sequences or multi-stage order checks through Laravel's built-in `Pipeline` facade to replace massive controller methods with testable steps.

When processing multi-stage workflows (like order checkout validation, user onboarding steps, or payload transformations), controllers quickly accumulate giant `if` blocks.

Laravel's `Pipeline` facade passes an object sequentially through dedicated pipe classes.

### Pipeline Execution

```php
use Illuminate\Support\Facades\Pipeline;
use App\Pipes\Orders\VerifyStock;
use App\Pipes\Orders\ApplyDiscountCode;
use App\Pipes\Orders\CalculateShipping;

$order = Pipeline::send($draftOrder)
    ->through([
        VerifyStock::class,
        ApplyDiscountCode::class,
        CalculateShipping::class,
    ])
    ->thenReturn();
```

### Implementing a Pipe Class

```php
namespace App\Pipes\Orders;

use Closure;
use App\Models\Order;

class VerifyStock
{
    public function handle(Order $order, Closure $next)
    {
        if (! $order->hasSufficientStock()) {
            throw new OutOfStockException('Item is no longer in stock.');
        }

        return $next($order);
    }
}
```

- Each pipe is a single-responsibility class with a `handle($passable, Closure $next)` signature
- Easily add, remove, or reorder pipeline stages without breaking other logic
- Each step can be unit tested independently
