---
category: "Laravel"
tags: ["Laravel", "Queue", "Deployment"]
date: "2026-08-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Global Queue Pause and Resume Controls in Laravel 13.25

> Laravel 13.25 introduces global queue pause and resume controls via queue:pause --all and Queue::pauseAll() to pause every queue across all connections during deployments.

During zero-downtime deployments or infrastructure migrations, workers need to finish in-flight jobs and hold off on picking up new ones while code and database changes run. Previously, pausing workers required targeting specific connections and queue names one by one, or placing the entire application into maintenance mode.

Laravel 13.25 introduces global queue pause and resume controls that affect every queue across all connections with a single command.

## Global Queue Controls

You can pause and resume all background queues via Artisan commands or programmatically through the `Queue` facade:

```bash
# Pause every queue on every connection
php artisan queue:pause --all

# Resume every queue after the deployment
php artisan queue:resume --all
```

```php
use Illuminate\Support\Facades\Queue;

// Pause all queues across all connections
Queue::pauseAll();

// Resume all queues after maintenance
Queue::resumeAll();
```

## Global vs Individually Paused Queues

The global pause state operates independently from individual queue pause states:

- Running `queue:pause --all` sets a global pause flag across all connections.
- Running `queue:resume --all` clears the global pause flag, but queues that were already paused individually (such as `php artisan queue:pause redis billing`) stay paused.

This intentional separation ensures a deployment script resuming all queues will not accidentally restart a problematic queue that an engineer intentionally paused earlier for debugging.

## Global Queue Events

Laravel 13.25 also dispatches dedicated global events when these operations run:

- `Illuminate\Queue\Events\QueuesPaused`: Dispatched when all queues are paused globally.
- `Illuminate\Queue\Events\QueuesResumed`: Dispatched when the global pause flag is cleared.

These differ from the per-queue events (`QueuePaused` and `QueueResumed`), which fire when specific individual queue names are paused or resumed.
