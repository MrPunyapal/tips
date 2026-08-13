---
category: "Laravel"
tags: ["Laravel", "Queue", "Events"]
date: "2026-08-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Handle Skipped Unique Jobs with UniqueJobSkipped

> Laravel 13.25 adds the UniqueJobSkipped event, allowing applications to react whenever a unique queue job is skipped because a duplicate instance is already active.

When background tasks prevent duplicate execution using unique locks, skipped dispatches previously occurred without firing a dedicated event. Laravel 13.25 introduces `Illuminate\Queue\Events\UniqueJobSkipped`, which is dispatched whenever a unique job is skipped.

## Define a Unique Job

Uniqueness is configured on the job class using the `ShouldBeUnique` interface and the `uniqueId()` method:

```php
namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductSearch implements ShouldQueue, ShouldBeUnique
{
    public function __construct(
        public Product $product,
    ) {}

    // Jobs for the same product share the same unique ID.
    public function uniqueId(): string
    {
        return (string) $this->product->id;
    }

    public function handle(): void
    {
        // Update the search index...
    }
}
```

The `ShouldBeUnique` contract instructs Laravel to prevent duplicate instances of the job from queuing concurrently. The `uniqueId()` method defines the unique identifier for lock key resolution. If multiple `UpdateProductSearch` jobs are dispatched for product `1`, they share the same unique ID `"1"`, causing Laravel to skip duplicate dispatches while the original job remains active.

## Listen for Skipped Jobs

You can register an event listener for `UniqueJobSkipped` in your `AppServiceProvider` or EventServiceProvider:

```php
use Illuminate\Queue\Events\UniqueJobSkipped;
use Illuminate\Support\Facades\Event;

Event::listen(UniqueJobSkipped::class, function ($event) {
    logger()->info(
        'Unique job skipped: ' . $event->job->uniqueId(),
        [
            'job' => $event->job,
        ]
    );
});
```

The event provides access to the underlying queue job instance via `$event->job`. Accessing `$event->job->uniqueId()` inside your listener includes the specific unique identifier in your logs, making it visible which job instance was skipped.

## Why This Is Useful

Listening to `UniqueJobSkipped` helps track queue deduplication across your application:

- **Finding Skipped Jobs**: Identify the specific unique job instance and ID that was suppressed.
- **Logging Duplicate Dispatches**: Keep detailed records of when redundant background tasks occur.
- **Monitoring Skipped Work**: Track metrics and frequency of skipped dispatches in application dashboards.
- **Investigating Suppressed Tasks**: Confirm whether expected work ran to completion or was suppressed as a duplicate.

Note that `ShouldBeUnique` is what enforces job deduplication, while `uniqueId()` determines the unique identifier. `UniqueJobSkipped` is an event dispatched after a job is skipped; it does not enforce uniqueness by itself or automatically log any data without an explicit event listener.
