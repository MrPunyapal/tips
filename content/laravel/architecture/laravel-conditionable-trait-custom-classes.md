---
category: "Laravel"
tags: ["Laravel", "Architecture", "Clean Code", "Design Patterns"]
date: "2022-11-02"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Add Fluent when() and unless() Logic to Custom Classes with Conditionable

> Use the Conditionable trait to equip your custom service classes, API clients, and query builders with fluent when() and unless() branching.

In Laravel, classes like the Eloquent Query Builder, Collection, and HTTP Client feature fluent `when()` and `unless()` methods.

You can add this exact fluent branching capability to your own domain classes by using the `Illuminate\Support\Traits\Conditionable` trait.

## Adding Conditionable to a Custom Service Class

```php
namespace App\Services;

use Illuminate\Support\Traits\Conditionable;

class InvoiceGenerator
{
    use Conditionable;

    protected array $items = [];
    protected float $discount = 0.0;
    protected bool $includeTax = true;

    public function applyDiscount(float $percentage): self
    {
        $this->discount = $percentage;
        return $this;
    }

    public function withoutTax(): self
    {
        $this->includeTax = false;
        return $this;
    }
}
```

## Fluent Chaining at the Call Site

```php
$isBlackFriday = true;
$isTaxExempt = $customer->is_tax_exempt;

$invoice = (new InvoiceGenerator())
    ->when($isBlackFriday, fn ($generator) => $generator->applyDiscount(20))
    ->when($isTaxExempt, fn ($generator) => $generator->withoutTax());
```

## Summary

- Injects `when($value, $callback, $defaultCallback)` and `unless($value, $callback)` into any PHP class.
- Removes clumsy intermediate `if` blocks when constructing configured service objects.
- Native to Laravel with zero external dependencies.
