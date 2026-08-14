---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2026-08-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Detect Eloquent Model Attribute Changes Before and After Saving

> Use isDirty() and isClean() before saving to inspect unsaved in-memory mutations, and use wasChanged() and getOriginal() after saving to verify persisted changes and access previous values.

When updating Eloquent models, tracking whether specific attributes have changed depends on whether the model has already been saved to the database. Laravel provides dedicated helper methods tailored for checking model state before and after saving.

## Inspecting Changes Before Saving

Before calling `save()`, changes exist only in memory on the model instance.

- `isDirty()`: Returns `true` if any attribute (or a specified attribute) has been modified since the model was loaded from the database.
- `isClean()`: The inverse of `isDirty()`. Returns `true` if an attribute remains unchanged.

```php
$user->name = 'Punyapal';

// Before saving: check current unsaved changes
$user->isDirty('name');  // true
$user->isClean('email'); // true
```

## Inspecting Changes After Saving

Once `save()` executes, the model synchronizes its in-memory attributes with the database. At this point, calling `isDirty()` returns `false` because there are no longer any unsaved modifications.

To inspect what changed during the save operation, use `wasChanged()` and `getOriginal()`:

- `wasChanged()`: Returns `true` if an attribute was updated during the most recent `save()` call.
- `getOriginal()`: Retrieves the attribute value as it was before mutations or before the save occurred.

```php
$user->save();

// After saving: check what changed during the last save
$user->wasChanged('name');  // true
$user->wasChanged('email'); // false

// Retrieve the original value
$user->getOriginal('name'); // previous name
```

## Practical Example: Email Verification & Notifications

A common practical use case is resetting email verification when an email address changes and sending a verification notification after saving:

```php
use App\Models\User;

$user = User::find(1);
$user->email = 'new-email@example.com';

// 1. Before saving: reset verification timestamp if email changed
if ($user->isDirty('email')) {
    $user->email_verified_at = null;
}

$user->save();

// 2. After saving: send verification notification only if email changed
if ($user->wasChanged('email')) {
    $user->sendEmailVerificationNotification();
}
```

This pattern ensures that:
1. `isDirty('email')` modifies companion in-memory attributes so both columns are written in a single database `UPDATE` query.
2. `wasChanged('email')` triggers external side effects (like sending emails) only after the changes have been successfully persisted.

## Key Takeaways

- Use `isDirty()` and `isClean()` prior to persistence to conditionally run validation or assign companion attributes.
- Use `wasChanged()` inside model events, observers, or service classes after calling `save()` to dispatch notifications only when relevant fields change.
- Pass attribute names as arguments (for example, `isDirty('email')` or `wasChanged('email')`) to check specific columns rather than the entire model.
- Use `getOriginal('column')` when you need the previous value for audit logging or old-vs-new comparison.
