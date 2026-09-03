---
category: "Laravel"
tags: ["Laravel", "Architecture", "Design Patterns"]
date: "2026-04-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Clean Up Complex Multi-Step Operations with Illuminate Pipeline

> Process complex data sequences or multi-stage order checks through Laravel's built-in Pipeline facade to replace massive controller methods.

When processing multi-stage workflows (such as order checkout validation or user onboarding steps), controller actions frequently accumulate deeply nested `if` statements.

Laravel's `Pipeline` facade passes an object sequentially through dedicated pipe classes.

---

## The Pipeline Runner

```php
use App\Models\Order;
use App\Pipes\ApplyCoupon;
use App\Pipes\CalculateTax;
use App\Pipes\VerifyStock;
use Illuminate\Support\Facades\Pipeline;

$order = Pipeline::send($draftOrder)
    ->through([
        VerifyStock::class,
        ApplyCoupon::class,
        CalculateTax::class,
    ])
    ->thenReturn();
```

---

## Anatomy of a Pipe Class

Each pipe implements a `handle()` method accepting the passable object and a `$next` closure:

```php
namespace App\Pipes;

use App\Models\Order;
use Closure;

class ApplyCoupon
{
    public function handle(Order $order, Closure $next)
    {
        if ($order->coupon_code) {
            $order->discount = 15.00;
        }

        // Pass the modified object to the next pipe in sequence
        return $next($order);
    }
}
```

---

## Key Benefits

- **Single Responsibility**: Each step is an isolated class with one clear focus.
- **Easy Reordering**: Add, remove, or reorder pipeline stages without touching adjacent logic.
- **Testability**: Test individual pipeline steps independently with unit tests.
