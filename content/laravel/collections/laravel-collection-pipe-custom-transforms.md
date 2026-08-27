---
category: "Laravel"
tags: ["Laravel", "Collections", "Clean Code"]
date: "2024-07-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Pass Collections into Custom Processors with Collection::pipe()

> Use Collection::pipe() to pass the entire collection into a custom closure or external service class without breaking method chaining.

When performing custom transformations or aggregations not supported natively by Collection methods (such as calculating standard deviation or passing the collection to a third-party chart formatter), breaking the chain into intermediate variables disrupts code flow.

`pipe()` passes the collection directly to a callback and returns the result.

## Basic Usage

```php
$sales = collect([120, 450, 300, 890, 620]);

// Calculate custom analytics fluently
$analytics = $sales
    ->filter(fn ($amount) => $amount > 200)
    ->pipe(function ($collection) {
        return [
            'total'   => $collection->sum(),
            'average' => $collection->avg(),
            'count'   => $collection->count(),
        ];
    });
```

## Passing to Dedicated Formatter Classes

```php
$chartData = User::where('active', true)
    ->get()
    ->pipe(new ChartDataTransformer());
```

## Summary

- Passes the current collection instance into any callable.
- Returns the callable's return value (which can be an array, object, or primitive).
- Preserves fluent pipeline formatting across complex business calculations.
