---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Macros"]
date: "2026-08-11"
author: "Vasile Papuc"
author_url: "https://www.linkedin.com/pulse/how-many-laravel-macros-do-you-know-vasile-papuc-wfuac/"
subcategory: "Eloquent"
---

# Define Custom Macros on the Eloquent Builder

> Register reusable query methods directly on the Builder so every model gains access without repeating logic.

Laravel's `Macroable` trait lets you add methods to the Builder at runtime. Register them in a service provider's `boot()` method and they become available on any query.

```php
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// In AppServiceProvider::boot()
Builder::macro('useIndex', function (string $table, string $index) {
    return $this->from(DB::raw("{$table} WITH (INDEX({$index}))"));
});
```

Now any model can hint a specific index:

```php
use App\Models\Order;

$orders = Order::query()
    ->useIndex('orders', 'orders_status_created_index')
    ->where('status', 'pending')
    ->get();
```

- Register on `Illuminate\Database\Eloquent\Builder` for Eloquent-level macros
- The closure receives `$this` bound to the current builder instance
- Useful for performance hints that aren't covered by the default query builder API
