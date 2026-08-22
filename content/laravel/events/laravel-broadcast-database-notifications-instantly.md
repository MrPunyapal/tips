---
category: "Laravel"
tags: ["Laravel", "Notifications", "WebSockets", "Events", "Real-Time"]
date: "2024-02-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Events"
---

# Instantly Broadcast Real-Time Database Notifications Using Model Observers

> Automatically broadcast WebSocket events to private user channels whenever a database notification is saved using an observer on Laravel's DatabaseNotification model.

Laravel's notification system supports both database persistence (`via: ['database']`) and real-time broadcasting (`via: ['broadcast']`). However, configuring notifications to broadcast simultaneously often duplicates payload formatting and requires configuring separate broadcast channels for each notification class.

By attaching an observer to Laravel's underlying `DatabaseNotification` model, you can automatically broadcast every database notification in real time over private channels with zero per-notification boilerplate.

## 1. Register the Observer in AppServiceProvider

```php
namespace AppProviders;

use IlluminateBroadcastingPrivateChannel;
use IlluminateNotificationsDatabaseNotification;
use IlluminateSupportServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        DatabaseNotification::observe(new class {
            public function created(DatabaseNotification $notification): void
            {
                broadcast(new class($notification) {
                    public function __construct(
                        public DatabaseNotification $notification
                    ) {}

                    public function broadcastOn(): PrivateChannel
                    {
                        // Derives channel from notifiable type (e.g. 'users.42')
                        $type = strtolower(class_basename(str($this->notification->notifiable_type)->plural()));
                        return new PrivateChannel("{$type}.{$this->notification->notifiable_id}");
                    }

                    public function broadcastAs(): string
                    {
                        return 'DatabaseNotificationCreated';
                    }
                });
            }
        });
    }
}
```

## 2. Listening with Laravel Echo (Frontend)

In your client-side JavaScript or Alpine.js component, subscribe to the user's private channel:

```javascript
Echo.private(`users.${userId}`)
    .listen('.DatabaseNotificationCreated', (e) => {
        console.log('New notification received:', e.notification);
    });
```

## 3. Listening in Livewire Components

In Livewire 3 components, use the `#[On]` attribute:

```php
namespace AppLivewire;

use LivewireAttributesOn;
use LivewireComponent;

class NotificationBell extends Component
{
    #[On('echo-private:users.{userId},.DatabaseNotificationCreated')]
    public function onNotificationReceived(array $payload): void
    {
        $this->dispatch('refreshNotifications');
    }
}
```

## Summary

- Observe `DatabaseNotification::created` to automatically broadcast real-time events for every stored database notification.
- Eliminates the need to duplicate broadcast payload definitions in individual notification classes.
- Seamlessly integrates with Laravel Echo, Livewire, and Vue/React frontend architectures.
