---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Observers", "Clean Code"]
date: "2025-06-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Inspect Model Modifications with isDirty(), wasChanged(), and getOriginal()

> Use isDirty(), wasChanged(), and getOriginal() to inspect modified attributes, verify column changes, and retrieve pre-update database values.

When building model observers or audit logging systems, determining whether specific attributes were modified (such as detecting an email or subscription tier change) requires comparing pre-save and post-save model states.

Eloquent provides state inspection methods on every model.

## Before Saving: isDirty() and getOriginal()

```php
$user = User::find(1);
$user->email = 'new-email@example.com';

// Check if any attributes or specific attributes were changed
if ($user->isDirty('email')) {
    // Retrieve previous value from database
    $oldEmail = $user->getOriginal('email');
    logger()->info("User {$user->id} changing email from {$oldEmail} to {$user->email}");
}

$user->save();
```

## After Saving: wasChanged()

Inside model `saved` observers or after `$model->save()`:

```php
if ($user->wasChanged('email')) {
    // Send verification email to the new address
    $user->sendEmailVerificationNotification();
}
```

## Summary

- `isDirty('col')`: Checks if attributes are modified in memory before saving.
- `getOriginal('col')`: Retrieves the unmodified attribute value as fetched from SQL.
- `wasChanged('col')`: Verifies if attributes were changed in the most recent save operation.
