---
category: "Laravel"
tags: ["Laravel", "Collections", "Clean Code"]
date: "2024-12-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Transform Non-Empty Datasets Fluently with whenNotEmpty()

> Use whenNotEmpty() on Laravel Collections to execute transformations or side effects only when collections contain items.

When transforming collections or sending batch notifications, wrapping collection pipelines in external `if ($collection->isNotEmpty())` blocks interrupts fluent method chaining.

`whenNotEmpty()` runs the given closure only if the collection contains one or more items.

## Basic Usage

```php
$pendingInvoices = Invoice::where('status', 'pending')->get();

$pendingInvoices
    ->whenNotEmpty(function ($invoices) {
        // Runs only if there is at least 1 invoice
        Notification::send(User::getFinanceTeam(), new BatchInvoiceAlert($invoices));
    })
    ->whenEmpty(function () {
        logger()->info('No pending invoices found for processing.');
    });
```

## Transforming Items Fluently

```php
$users = collect($rawUsers)
    ->whenNotEmpty(function ($collection) {
        return $collection->sortBy('created_at')->values();
    });
```

## Summary

- Executes closure only if `$collection->count() > 0`.
- Complements `whenEmpty()` for complete conditional pipeline flows.
- Maintains fluent chaining without external `if` statements.
