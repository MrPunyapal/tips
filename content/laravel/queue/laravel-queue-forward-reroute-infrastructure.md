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

Rerouting background job traffic (such as moving high-throughput workloads from a shared Redis worker pool to dedicated Amazon SQS FIFO queues) traditionally required updating every job class property and manual dispatch call (`->onQueue(...)`, `->onConnection(...)`).

Laravel 13.26 adds `Queue::forward()`, allowing you to centrally intercept and redirect queue traffic at the application boot layer.

---

## Centralized Configuration

Register forwarding rules centrally in your `AppServiceProvider::boot()` method:

```php
namespace App\Providers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Reroute both queue name and connection (e.g. migrating to SQS FIFO)
        Queue::forward('reports', 'reports.fifo', connection: 'sqs-fifo');

        // 2. Shift connection driver while retaining the original queue name
        Queue::forward('payments', connection: 'stripe-workers');

        // 3. Rename a queue channel on the default connection
        Queue::forward('legacy-updates', 'notifications');

        // 4. Batch forward multiple queues to another connection
        Queue::forward([
            'emails'    => 'emails.fifo',
            'webhooks'  => 'webhooks.fifo',
        ], connection: 'sqs-fifo');
    }
}
```

---

## Dispatch Code Remains Unchanged

Application dispatch sites and job classes continue referencing their original queue names:

```php
// Dispatch code remains completely untouched
GenerateMonthlyReport::dispatch($report)->onQueue('reports');
```

Laravel resolves forwarding through its internal queue routing pipeline, transparently redirecting the job to `reports.fifo` on the `sqs-fifo` connection.
