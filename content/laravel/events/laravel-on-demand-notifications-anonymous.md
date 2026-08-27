---
category: "Laravel"
tags: ["Laravel", "Notifications", "Mail", "Architecture"]
date: "2024-01-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Events"
---

# Send On-Demand Notifications Without User Models with Notification::route()

> Use Notification::route() to send emails, SMS messages, or Slack webhooks to ad-hoc recipients without creating database User records.

Laravel's notification system is typically tied to models with the `Notifiable` trait (such as `$user->notify(...)`). When sending alerts to third-party vendors, external email addresses, or webhook endpoints, creating temporary database models is unnecessary.

`Notification::route()` allows dispatching notifications to anonymous, on-demand channels.

## Sending an Email Notification to an External Address

```php
use App\Notifications\SecurityAlertNotification;
use Illuminate\Support\Facades\Notification;

// Send email to an external security address
Notification::route('mail', 'security@company.com')
    ->notify(new SecurityAlertNotification($suspiciousActivity));
```

## Routing to Multiple Anonymous Channels

```php
use App\Notifications\ServerDownNotification;
use Illuminate\Support\Facades\Notification;

Notification::route('mail', ['admin@example.com' => 'System Admin'])
    ->route('vonage', '+15551234567')
    ->route('slack', 'https://hooks.slack.com/services/...')
    ->notify(new ServerDownNotification($server));
```

## Summary

- Delivers notifications without requiring an Eloquent `User` instance.
- Supports all standard notification drivers (mail, vonage/sms, slack, custom webhooks).
- Ideal for system monitoring alerts, external vendor updates, and contact form auto-responders.
