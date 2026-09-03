---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Architecture", "Clean Code"]
date: "2023-09-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Encapsulate Complex Data Transformations with Custom Eloquent Casts

> Implement the CastsAttributes interface to create reusable custom value object casts for Eloquent model attributes.

When database columns store custom structures (such as storing monetary amounts in cents, encrypted payloads, or coordinate objects), converting them manually in accessors and mutators across models creates duplicate logic.

Custom Cast classes encapsulate two-way transformation logic cleanly.

## Generating a Custom Cast

```bash
php artisan make:cast MoneyCast
```

## Implementing CastsAttributes

```php
namespace App\Casts;

use App\ValueObjects\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        return $value !== null ? new Money((int) $value) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        return $value instanceof Money ? $value->getAmountInCents() : $value;
    }
}
```

## Applying to Models

```php
namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $casts = [
        'price' => MoneyCast::class,
    ];
}
```

## Summary

- Encapsulates bi-directional serialization (`get` from DB, `set` to DB).
- Turns raw database integers and strings into rich Value Objects.
- Reusable across all application models.
