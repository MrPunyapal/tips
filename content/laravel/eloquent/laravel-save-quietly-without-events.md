---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Events", "Observers"]
date: "2023-01-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Update Models Without Triggering Observers Using saveQuietly()

> Perform updates, insertions, and deletions without firing Eloquent events or model observers using saveQuietly() and withoutEvents().

Model observers and event listeners often trigger side effects (such as sending notifications, regenerating search indexes, or updating audit logs). When running data migrations, background synchronizations, or internal status increments, you may want to update attributes without triggering these events.

## Using saveQuietly()

`saveQuietly()` saves the model without firing any `saving`, `saved`, `updating`, or `updated` events:

```php
use App\Models\User;

$user = User::find(1);
$user->last_login_at = now();

// Updates database without triggering UserObserver
$user->saveQuietly();
```

## Deleting and Restoring Quietly

Laravel also provides companion methods for quiet deletion and restoration:

```php
$user->deleteQuietly();
$user->restoreQuietly();
```

## Running Multiple Operations with withoutEvents()

To execute a block of multiple database operations without any model events:

```php
use App\Models\Order;

Order::withoutEvents(function () {
    Order::where('status', 'draft')
        ->where('created_at', '<', now()->subMonths(6))
        ->update(['status' => 'archived']);
});
```

## Summary

- `saveQuietly()` prevents infinite recursion when modifying models inside observer callbacks.
- `deleteQuietly()` and `restoreQuietly()` provide silent lifecycle actions.
- `Model::withoutEvents(fn () => ...)` suppresses all model events across multiple operations.
