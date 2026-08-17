---
category: "Laravel"
tags: ["Laravel", "Events", "Queue"]
date: "2026-08-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Queue Anonymous Event Listeners with queueable()

> Wrap closure-based event listeners with the queueable() helper to push them to background queues without creating dedicated listener classes.

When registering event listeners in Laravel, standard closures execute synchronously within the current request cycle. If a listener performs slower work like sending an email or notifying a third-party API, developers typically scaffold a full listener class implementing `ShouldQueue`.

For small or single-purpose tasks, you can use the `Illuminate\Events\queueable` helper function to queue an anonymous closure directly while retaining complete control over connection, queue, delay, and failure handling.

## Queueing a Closure Listener

Wrap your listener closure with `queueable()` when calling `Event::listen()`:

```php
use App\Events\OrderPlaced;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Throwable;
use function Illuminate\Events\queueable;

Event::listen(
    queueable(function (OrderPlaced $event) {
        Mail::to($event->order->user->email)
            ->send(new OrderConfirmation($event->order));
    })
        ->onConnection('redis')
        ->onQueue('orders')
        ->delay(now()->addSeconds(10))
        ->catch(function (OrderPlaced $event, Throwable $e) {
            logger()->error('Order confirmation failed', [
                'order_id' => $event->order->id,
                'error' => $e->getMessage(),
            ]);
        })
);
```

## How It Works

Under the hood, `queueable()` wraps the closure in an instance of `Illuminate\Events\QueuedClosure`. When the event fires, Laravel serializes the closure and dispatches it as an asynchronous queue job instead of executing it inline.

The `QueuedClosure` object provides fluent chaining methods matching standard queued job options:

- `onConnection('redis')`: Specifies which database or queue connection handles the listener.
- `onQueue('orders')`: Routes the listener to a specific named queue.
- `delay(now()->addSeconds(10))`: Postpones execution by a given duration.
- `catch(callable)`: Defines a fallback callback executed if the queued listener exhausts its retry attempts or encounters an unhandled exception.

## When to Use Queueable Closures

Queueable anonymous listeners work well for:
- Lightweight notifications and transactional emails tied directly to an event.
- Simple cache invalidation or audit logging.
- Prototypes and small applications where creating dedicated listener files adds boilerplate without architectural benefits.

## When to Use Dedicated Listener Classes

Stick with dedicated listener classes (`php artisan make:listener SendOrderConfirmation --queued`) when:
- The listener involves complex orchestration with multiple injected dependencies.
- You need isolated unit or integration tests specifically targeting the listener class.
- The listener logic is shared across multiple events or needs reuse throughout the codebase.
