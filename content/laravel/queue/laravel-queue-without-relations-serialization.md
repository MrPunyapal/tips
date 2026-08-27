---
category: "Laravel"
tags: ["Laravel", "Queue", "Performance", "Architecture"]
date: "2025-12-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Prevent Queue Payload Bloat with withoutRelations()

> Use withoutRelations() when passing models to queue jobs to prevent large in-memory relationship graphs from serializing into queue storage.

When dispatching queue jobs that accept an Eloquent model instance, Laravel serializes the model. If the model had dozens of nested relationships eager-loaded in the controller, all those related models are serialized into the queue payload (Redis / Database), causing massive payload bloat.

Calling `withoutRelations()` strips loaded relations before dispatching.

## Basic Usage

```php
use App\Jobs\GenerateUserInvoiceJob;

public function checkout(Order $order)
{
    // $order has heavy loaded relations (items, customer, history)

    // Strips loaded relations so only the primary key is serialized
    GenerateUserInvoiceJob::dispatch($order->withoutRelations());
}
```

## In Job Class Constructors

Alternatively, clean relations in the constructor:

```php
namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->withoutRelations();
    }
}
```

## Summary

- Reduces serialized queue payload sizes from megabytes to bytes.
- Prevents `Redis command size exceeded` or long text column limits in `jobs` tables.
- Queue workers re-hydrate fresh relationships when needed.
