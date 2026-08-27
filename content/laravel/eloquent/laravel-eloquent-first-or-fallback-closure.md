---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2025-09-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Execute Custom Fallbacks on Missing Records with firstOr()

> Use firstOr() to retrieve the first matching database record or execute a custom fallback closure when no record is found.

When searching for a record where failure requires a specific fallback action (such as creating a guest profile, dispatching an invitation, or throwing a domain-specific exception), writing manual `if (! $model = Model::where(...)->first())` checks adds boilerplate.

`firstOr()` executes a closure callback when the query returns null.

## Basic Fallback Closure

```php
use App\Models\User;

$user = User::where('email', $email)->firstOr(function () use ($email) {
    // Executes ONLY IF no record matched
    return User::create([
        'email'   => $email,
        'role'    => 'guest',
        'is_temp' => true,
    ]);
});
```

## Throwing Custom Domain Exceptions

```php
use App\Exceptions\SubscriptionRequiredException;

$subscription = $user->subscriptions()
    ->where('is_active', true)
    ->firstOr(fn () => throw new SubscriptionRequiredException('Active subscription required.'));
```

## Summary

- Returns the first matching `Model` instance or the result of the closure.
- Keeps fallback generation logic localized to the query call site.
- Cleaner alternative to separate null checks and manual exception throwing.
