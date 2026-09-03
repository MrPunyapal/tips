---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Transactions"]
date: "2026-08-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Refresh and Pessimistically Lock Models with refreshForUpdate() in Laravel 13.27

> Laravel 13.27 adds refreshForUpdate(), reloading an existing model instance in-place with fresh database attributes while acquiring an exclusive FOR UPDATE row lock.

When an Eloquent model is loaded before a transaction begins (such as through route model binding or an earlier service method), its memory state can drift from the database before you mutate it.

Previously, refreshing and locking an existing model instance required re-querying the table and reassigning the variable. Laravel 13.27 provides `refreshForUpdate()` to handle this directly on the existing instance.

---

## Before & After

```php
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

public function purchase(Product $product): Response
{
    DB::transaction(function () use ($product) {
        // Before Laravel 13.27: manual query lookup and reassignment
        // $product = Product::lockForUpdate()->findOrFail($product->getKey());

        // Laravel 13.27+: refreshes attributes in place with a row lock
        $product->refreshForUpdate();

        if ($product->stock < 1) {
            throw new RuntimeException('The product is out of stock.');
        }

        $product->decrement('stock');
    });

    return response()->noContent();
}
```

---

## Key Takeaways

- **In-Place Mutation**: Refreshes the existing model object and syncs its original attributes without requiring variable reassignment.
- **Under the Hood**: Uses `$this->newQueryWithoutScopes()->lockForUpdate()` targeting the model's primary key.
- **Transaction Requirement**: Must be called inside `DB::transaction()` or an active transaction block for the database engine to maintain the row lock.
- **Common Use Cases**: Prevents race conditions during inventory decrements, wallet balances, or seat reservations when models originate from route model binding.
