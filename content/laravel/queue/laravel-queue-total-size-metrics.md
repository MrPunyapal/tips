---
category: "Laravel"
tags: ["Laravel", "Queue", "Monitoring", "Architecture", "DevOps"]
date: "2026-08-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Monitor Aggregate Queue Metrics with Total Size Helpers in Laravel 13.27

> Laravel 13.27 adds totalPendingSize(), totalDelayedSize(), and totalReservedSize() to the Queue facade, making it easy to aggregate queue metrics for dashboards and health checks.

When building admin panels, operational dashboards, or custom health-check endpoints, tracking queue volume across your application is a common requirement.

Prior to Laravel 13.27, `Queue::size($queue)` only reported pending counts for a single named queue. Getting total metrics across multiple queues required manually querying each individual queue and summing the counts in application code.

**Laravel 13.27 introduces dedicated aggregate size helpers** directly on the `Queue` facade.

---

## 1. Laravel 13.27 Total Size Helpers

```php
use Illuminate\Support\Facades\Queue;

$pending = Queue::totalPendingSize();
// Jobs waiting to be processed.

$delayed = Queue::totalDelayedSize();
// Jobs intentionally delayed for later.

$reserved = Queue::totalReservedSize();
// Jobs currently reserved by workers.
```

---

## 2. Understanding Each Queue Metric

- **`totalPendingSize()`**: The total count of ready jobs waiting in queues to be claimed by worker processes. A sustained spike indicates worker starvation or backlog accumulation.
- **`totalDelayedSize()`**: The total count of jobs scheduled with `delay()` that are waiting for their release timestamp to expire before becoming pending.
- **`totalReservedSize()`**: The total count of jobs currently claimed and actively being executed by worker processes.

---

## 3. Practical Example: Lightweight Dashboard & Health Endpoints

These helpers make it simple to assemble lightweight queue health payloads without setting up heavy external monitoring packages:

```php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;

class QueueMetricsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $pending  = Queue::totalPendingSize();
        $delayed  = Queue::totalDelayedSize();
        $reserved = Queue::totalReservedSize();

        return response()->json([
            'status'   => $pending > 5000 ? 'degraded' : 'healthy',
            'metrics'  => [
                'pending'   => $pending,
                'delayed'   => $delayed,
                'reserved'  => $reserved,
                'total'     => $pending + $delayed + $reserved,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
```

---

## 4. Querying Specific Connections

By default, the helpers aggregate metrics across the default queue connection. You can target specific connections when running multiple queue backends (like separate Redis and database queues):

```php
use Illuminate\Support\Facades\Queue;

// Query a dedicated Redis queue connection
$redisPending = Queue::connection('redis')->totalPendingSize();

// Query a database fallback connection
$dbDelayed = Queue::connection('database')->totalDelayedSize();
```

*(Note: These helpers provide fast aggregate totals for lightweight reporting. For detailed per-job telemetry, throughput graphs, and runtime tags, use Laravel Horizon or Laravel Pulse.)*

---

## Summary

- `Queue::totalPendingSize()` returns all jobs waiting to be processed.
- `Queue::totalDelayedSize()` returns all delayed jobs awaiting release.
- `Queue::totalReservedSize()` returns all jobs actively reserved by workers.
- Avoids manual multi-queue loop-and-sum aggregation.
- Supports chaining `connection('name')` for multi-backend setups.
