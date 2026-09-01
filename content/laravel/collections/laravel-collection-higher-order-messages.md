---
category: "Laravel"
tags: ["Laravel", "Collections", "Eloquent", "PHP", "Clean Code"]
date: "2026-09-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Simplify Collection Operations with Higher-Order Messages

> Laravel Collections support higher-order messages through dynamic properties, allowing operations like each, map, filter, and groupBy to work directly with an item's property or method without writing boilerplate callbacks.

When manipulating collections in Laravel, developers frequently pass anonymous functions that do nothing more than read a single object property or invoke a single method.

Writing boilerplate closures for simple property forwarding adds unnecessary visual noise.

Laravel provides **higher-order messages** via proxy properties on collections, allowing you to access item attributes and methods directly on the collection itself.

---

## The Core Concept: Removing Boilerplate Callbacks

Consider grouping a collection of orders by their status:

### Before

```php
$orders->groupBy(function (Order $order) {
    return $order->status;
});
```

### After

```php
$orders->groupBy->status;
```

The closure existed only to access the `status` property. With higher-order messages, Laravel intercepts the property access and applies it to each item in the collection automatically.

---

## How Higher-Order Messages Work

Higher-order messages are accessed as dynamic properties on the Collection instance (`$collection->methodName->target`).

Behind the scenes, Laravel returns a `HigherOrderCollectionProxy` instance. When you access a property or call a method on this proxy, Laravel applies that operation to each item during the collection loop:

```text
$collection->each->markAsVip()
         ↓
HigherOrderCollectionProxy
         ↓
Loops items and invokes $item->markAsVip()
```

---

## Property Access vs. Method Calls

Depending on the collection method and what each item exposes, higher-order messages can forward either a **property access** or a **method invocation**:

### 1. Reading an Attribute (Property Access)

When accessing model attributes or array keys:

```php
// Sum the total property across all orders
$totalRevenue = $orders->sum->total;

// Group items by category property
$groupedProducts = $products->groupBy->category;

// Sort users by created_at timestamp descending
$recentUsers = $users->sortByDesc->created_at;
```

### 2. Invoking an Action (Method Call)

When running a method on each item in the collection:

```php
// Execute markAsVip() on each User instance
$users->each->markAsVip();

// Filter users where isActive() returns true
$activeUsers = $users->filter->isActive();

// Reject expired subscriptions
$validSubscriptions = $subscriptions->reject->isExpired();
```

---

## Real-World Practical Examples

```php
namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Collection;

class BillingService
{
    /**
     * @param Collection<int, User> $users
     */
    public function processVipMembers(Collection $users): void
    {
        // 1. Filter active users
        $activeVips = $users->filter->isVip();

        // 2. Perform an action on each
        $activeVips->each->sendVipNewsletter();
    }

    /**
     * @param Collection<int, Invoice> $invoices
     */
    public function summarizeInvoices(Collection $invoices): array
    {
        return [
            // Group invoices by payment status
            'by_status'     => $invoices->groupBy->status,
            // Calculate total unpaid balance
            'unpaid_total'  => $invoices->filter->isUnpaid()->sum->amount,
            // Find highest single invoice
            'max_invoice'   => $invoices->max->amount,
            // Extract unique customer IDs
            'customer_ids'  => $invoices->unique->customer_id->map->customer_id,
        ];
    }
}
```

---

## Complete Supported Method Reference

Higher-order messages are supported across 23 Collection methods, organized by category:

```php

// Aggregation: calculate values from a property or method.
$collection->average->property;
$collection->avg->property;
$collection->max->property;
$collection->min->property;
$collection->sum->property;

// Iteration: call methods or transform items without callbacks.
$collection->each->method();
$collection->map->property;
$collection->flatMap->method();

// Filtering: keep, remove, or check items using a property or method.
$collection->contains->method();
$collection->every->method();
$collection->filter->method();
$collection->reject->method();
$collection->some->method();

// Finding & grouping: locate, group, partition, or key items.
$collection->first->method();
$collection->groupBy->property;
$collection->keyBy->property;
$collection->partition->method();
$collection->unique->property;

// Sorting: order items using a property or method.
$collection->sortBy->property;
$collection->sortByDesc->property;

// Skipping & taking: include or skip items based on conditions.
$collection->skipUntil->method();
$collection->skipWhile->method();
$collection->takeUntil->method();
$collection->takeWhile->method();
```

---

## When to Keep the Closure

Higher-order messages are designed for direct property reads and single method calls. If your callback involves custom logic, comparisons, or multi-step transformations, continue using standard closures:

```php
// Keep a closure when custom business logic or conditionals are needed:
$orders->groupBy(function (Order $order) {
    return $order->total > 1000 ? 'tier_enterprise' : 'tier_standard';
});

// Keep a closure when multiple arguments or external variables are required:
$users->each(function (User $user) use ($notifier, $discountCode) {
    $notifier->sendCustomPromotion($user, $discountCode);
});
```

The general rule: **Use higher-order messages when a callback only forwards a property or method; keep a closure when you need logic.**

---

## Summary

- Higher-order messages eliminate boilerplate closures across 23 Laravel Collection methods.
- Accessed via dynamic properties on the collection (`$collection->filter->isActive()`).
- Supports both property retrieval (`$orders->sum->total`) and method execution (`$users->each->delete()`).
- Keeps chained collection pipelines readable and concise without sacrificing clarity.
