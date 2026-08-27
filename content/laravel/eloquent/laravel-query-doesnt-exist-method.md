---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2023-06-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Write Readable Query Negations with doesntExist()

> Use doesntExist() instead of ! exists() to make conditional database checks clean and expressive.

Checking whether records do not exist in the database is a common requirement (such as verifying slug uniqueness or confirming an invite has not been claimed).

Instead of prefixing `exists()` with an exclamation point (`! $query->exists()`), Laravel provides the expressive `doesntExist()` method.

## Basic Usage

```php
use App\Models\User;

// Before: Easy to overlook the leading exclamation mark
if (! User::where('email', $email)->exists()) {
    // ...
}

// After: Clear and descriptive
if (User::where('email', $email)->doesntExist()) {
    // ...
}
```

## Checking Relationships

`doesntExist()` works on any Eloquent relationship query:

```php
if ($user->orders()->where('status', 'completed')->doesntExist()) {
    // User has never completed an order
    $user->sendWelcomeDiscountNotification();
}
```

## Aborting on Missing Records with doesntExistOr()

You can also execute a closure or abort when no records exist:

```php
User::where('api_token', $token)->doesntExistOr(function () {
    abort(401, 'Invalid API Token.');
});
```

## Summary

- Eliminates easily overlooked `!` negation symbols in conditional logic.
- Returns a boolean without hydrating model instances into memory.
- Improves code readability in validation and business rule checks.
