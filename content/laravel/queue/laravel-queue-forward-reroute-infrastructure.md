---
category: "Laravel"
tags: ["Laravel", "Queue", "Architecture", "DevOps"]
date: "2026-08-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Centralized Queue Routing with Queue::forward() in Laravel 13.26

> Laravel 13.26 introduces Queue::forward() to reroute queued jobs to new queue names, different connections, or both without modifying job classes or dispatch sites.

As applications scale and infrastructure requirements change, you frequently need to re-architect queue backends. For example, you might need to move heavy background workloads from a shared Redis connection to a dedicated Amazon SQS FIFO queue, or rename legacy queue channels across your application.

Previously, redirecting queue traffic required finding and updating every job dispatch call (`->onQueue(...)`, `->onConnection(...)`) or updating individual job class properties.

Laravel 13.26 introduces `Queue::forward()`, providing a centralized routing layer to redirect queue destinations without modifying application dispatch code.

## How Queue::forward() Works

You can configure queue forwarding across individual queues or bulk mappings:

```php
use Illuminate\Support\Facades\Queue;

// Move a queue to a new queue name and a different connection
Queue::forward('reports', 'reports.fifo', 'cloud');

// Move a queue to another connection while keeping the same queue name
Queue::forward('payments', connection: 'cloud');

// Rename a queue on the current connection
Queue::forward('updates', 'notifications');

// Forward multiple queues together using an array mapping
Queue::forward([
    'reports' => 'reports.fifo',
    'emails' => 'emails.fifo',
], connection: 'cloud');
```

## Forwarding Variations

### 1. Changing Both Queue Name and Connection

When migrating to services like Amazon SQS FIFO, both the queue name (requiring a `.fifo` suffix) and the connection driver need to change:

```php
// Jobs targeting 'reports' are sent to 'reports.fifo' on the 'sqs-fifo' connection
Queue::forward('reports', 'reports.fifo', 'sqs-fifo');
```

### 2. Moving Only the Connection

If you only need to shift traffic to a dedicated worker pool or separate queue driver without renaming the queue:

```php
// Jobs targeting 'payments' stay on the 'payments' queue but route to the 'cloud' connection
Queue::forward('payments', connection: 'cloud');
```

### 3. Renaming on the Same Connection

When standardizing naming conventions across internal queues:

```php
// Jobs targeting 'updates' are transparently routed to 'notifications'
Queue::forward('updates', 'notifications');
```

## Centralized Configuration in Service Providers

`Queue::forward()` is designed to be registered centrally within your application's service provider boot lifecycle:

```php
namespace App\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Centralized queue routing rules
        Queue::forward([
            'reports' => 'reports.fifo',
            'emails' => 'emails.fifo',
        ], connection: 'sqs-fifo');

        Queue::forward('payments', connection: 'stripe-workers');
    }
}
```

Because forwarding resolves internally through Laravel's queue routing pipeline (the same mechanism used by `Queue::route()`), the rerouting rules apply consistently across all dispatched jobs.

## What Stays Unchanged

The main advantage of `Queue::forward()` is decoupling infrastructure topology from application code. Your existing job classes and dispatch sites remain untouched:

```php
// Application dispatch code remains completely unchanged
GenerateMonthlyReport::dispatch($report)
    ->onQueue('reports');

ProcessCustomerPayment::dispatch($payment)
    ->onQueue('payments');
```

Even though `GenerateMonthlyReport` explicitly targets `reports`, Laravel intercepts the dispatch and routes it to `reports.fifo` on the configured connection.

## Practical Use Cases

- **Queue Infrastructure Migrations**: Transition high-throughput queues from Redis or MySQL queue drivers to AWS SQS or RabbitMQ without mass refactoring.
- **SQS FIFO Adoption**: Add `.fifo` suffixes and dedicated connections to existing workloads that require strict message ordering.
- **Gradual Architecture Reorganization**: Consolidate scattered legacy queues into unified processing pipelines centrally.
- **Environment-Specific Routing**: Route specific queues to local sync/database connections in development while forwarding them to dedicated cloud workers in production.

## Summary

`Queue::forward()` provides a centralized way to reroute existing queue traffic to new queues or connections without modifying the jobs or dispatch sites that produce them.
