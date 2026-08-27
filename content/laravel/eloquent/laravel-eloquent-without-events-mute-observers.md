---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Observers", "Performance"]
date: "2024-09-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Mute Model Observers During Bulk Updates with Model::withoutEvents()

> Use Model::withoutEvents() to execute model operations while temporarily suppressing all Eloquent events and observers.

When running mass database seeds, batch background imports, or administrative migrations, model observers that dispatch search index syncs, audit logs, or webhooks can severely slow down execution or trigger thousands of unwanted side effects.

`Model::withoutEvents()` mutes all model events for the duration of the closure.

## Basic Usage

```php
use App\Models\User;

// Imports 1,000 users without triggering UserObserver, emails, or Search indexing
User::withoutEvents(function () use ($csvRows) {
    foreach ($csvRows as $row) {
        User::create([
            'name'  => $row['name'],
            'email' => $row['email'],
        ]);
    }
});
```

## Scoped to Specific Model Classes

Event muting is strictly scoped to the model class specified and automatically restored immediately after the closure returns:

```php
Order::withoutEvents(fn () => $order->update(['status' => 'archived']));
```

## Summary

- Suppresses `creating`, `created`, `updating`, `updated`, `deleting`, and `deleted` events.
- Essential optimization for batch seeders, data migrations, and bulk imports.
- Re-enables event listeners automatically upon completion.
