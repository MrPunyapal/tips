---
category: "Laravel"
tags: ["Laravel", "Enums", "PHP 8.1+", "Clean Code"]
date: "2024-09-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Replace Magic Strings with PHP 8 Backed Enums in Eloquent Models

> Use PHP 8 Backed Enums to eliminate magic strings in database columns, query scopes, and form validation rules.

Hardcoding status strings like `$order->status === 'pending'` or `User::where('role', 'admin')` across multiple controllers makes refactoring difficult and introduces silent typo bugs that static analysis tools cannot catch.

PHP 8 Backed Enums provide type safety and IDE autocomplete.

## Defining a Backed Enum

```php
namespace App\Enums;

enum OrderStatus: string
{
    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case CANCELLED  = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING    => 'Order Pending',
            self::PROCESSING => 'In Processing',
            self::COMPLETED  => 'Order Completed',
            self::CANCELLED  => 'Cancelled',
        };
    }
}
```

## Casting in Eloquent Models

```php
namespace AppModels;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }
}
```

## Type-Safe Querying and Comparison

```php
use App\Enums\OrderStatus;

// Type-safe query: No magic strings
$orders = Order::where('status', OrderStatus::PENDING)->get();

// Natural enum comparison
if ($order->status === OrderStatus::COMPLETED) {
    // Process fulfillment
}
```

## Summary

- Prevents typos and catches invalid status assignments during compilation.
- Provides native casting with Eloquent models via `casts()`.
- Bundles domain display labels and helper methods directly inside Enum classes.
