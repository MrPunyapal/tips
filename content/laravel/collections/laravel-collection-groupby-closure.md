---
category: "Laravel"
tags: ["Laravel", "Collections", "PHP", "Dates"]
date: "2023-10-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Dynamic Date Grouping in Laravel Collections Using Closures in groupBy()

> Pass a closure to Laravel Collection's groupBy() method to group models and arrays dynamically by day, week, month, or quarter.

Laravel's `Collection::groupBy()` method is commonly used with a simple string key (such as `->groupBy('status')`).

However, `groupBy()` also accepts a closure that receives each item in the collection and returns the dynamic group key. This is especially useful for aggregating records into date intervals such as weeks, quarters, or formatted periods for reporting dashboards.

## Grouping Records by Dynamic Time Periods

```php
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

$orders = Order::query()
    ->where('created_at', '>=', now()->subYear())
    ->get();

$interval = request('interval', 'week'); // 'day', 'week', 'month', 'quarter', 'year'

$groupedOrders = $orders->groupBy(function (Order $order) use ($interval): string {
    $date = $order->created_at;

    return match ($interval) {
        'day' => $date->format('D d M Y'),
        // Output: "Sun 01 Jan 2026"

        'week' => $date->copy()->startOfWeek()->format('d M Y') . ' - ' . $date->copy()->endOfWeek()->format('d M Y'),
        // Output: "01 Jan 2026 - 07 Jan 2026"

        'month' => $date->format('M Y'),
        // Output: "Jan 2026"

        'quarter' => $date->copy()->firstOfQuarter()->format('M') . ' - ' . $date->copy()->lastOfQuarter()->format('M Y'),
        // Output: "Jan - Mar 2026"

        'year' => $date->format('Y'),
        // Output: "2026"
    };
});
```

## Structure of the Resulting Collection

The returned collection uses the formatted date strings as keys, with each entry containing the subset of matching models:

```php
// Example quarter grouping:
[
    "Jan - Mar 2026" => Collection [ Order #101, Order #102 ],
    "Oct - Dec 2025" => Collection [ Order #89, Order #90 ],
]
```

## Aggregating Data per Period

Once grouped, you can chain standard collection operations like `map()` or `sum()` to build dashboard statistics:

```php
$revenueByQuarter = $groupedOrders->map(function (Collection $orders): float {
    return $orders->sum('total_amount');
});
```

## Summary

- Pass a closure to `Collection::groupBy()` for dynamic, computed grouping criteria.
- Pair with Carbon's period helpers (`startOfWeek()`, `firstOfQuarter()`) to group data into reporting intervals.
- Chain `map()` and `sum()` on grouped collections to calculate interval metrics effortlessly.
