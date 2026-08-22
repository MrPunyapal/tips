---
category: "Laravel"
tags: ["Laravel", "Events", "Logging", "Architecture", "Audit"]
date: "2023-12-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Events"
---

# Centralized Application Activity Logging with Laravel Event Subscribers

> Capture HTTP requests, Artisan CLI commands, and outgoing API responses into a unified audit trail using a single Laravel Event Subscriber.

Applications frequently need to log key lifecycle actions for debugging and compliance. Instead of scattering logging logic across various middleware, commands, and service classes, Laravel Event Subscribers let you listen to multiple framework events from a single centralized class.

## The ActivityLogEventSubscriber

```php
namespace AppListeners;

use IlluminateConsoleEventsCommandFinished;
use IlluminateEventsDispatcher;
use IlluminateFoundationHttpEventsRequestHandled;
use IlluminateHttpClientEventsResponseReceived;
use IlluminateSupportFacadesLog;

class ActivityLogEventSubscriber
{
    public function handleHttpRequest(RequestHandled $event): void
    {
        $this->logActivity('RequestHandled', [
            'method' => $event->request->method(),
            'url' => $event->request->fullUrl(),
            'status' => $event->response->getStatusCode(),
            'user_id' => $event->request->user()?->id,
        ]);
    }

    public function handleConsoleCommand(CommandFinished $event): void
    {
        $this->logActivity('CommandFinished', [
            'command' => $event->command,
            'exit_code' => $event->exitCode,
        ]);
    }

    public function handleHttpClientResponse(ResponseReceived $event): void
    {
        $this->logActivity('HttpClientResponse', [
            'url' => (string) $event->request->url(),
            'status' => $event->response->status(),
        ]);
    }

    private function logActivity(string $eventType, array $payload): void
    {
        Log::channel('activity')->info("Activity [{$eventType}]", $payload);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            RequestHandled::class => 'handleHttpRequest',
            CommandFinished::class => 'handleConsoleCommand',
            ResponseReceived::class => 'handleHttpClientResponse',
        ];
    }
}
```

## Registering the Subscriber

In Laravel 11+, register the subscriber inside your `AppServiceProvider::boot()` method (or `EventServiceProvider` in earlier versions):

```php
namespace AppProviders;

use AppListenersActivityLogEventSubscriber;
use IlluminateSupportFacadesEvent;
use IlluminateSupportServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::subscribe(ActivityLogEventSubscriber::class);
    }
}
```

## Summary

- Use an Event Subscriber to group related event handlers into a single cohesive class.
- Listen to built-in framework events like `RequestHandled`, `CommandFinished`, and `ResponseReceived` for comprehensive audit logging.
- Decouples monitoring and logging logic from core HTTP controllers and console commands.
