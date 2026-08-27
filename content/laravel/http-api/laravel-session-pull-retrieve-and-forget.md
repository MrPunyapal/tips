---
category: "Laravel"
tags: ["Laravel", "Session", "HTTP", "Clean Code"]
date: "2023-04-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Retrieve and Delete Session Data in One Step with session()->pull()

> Use session()->pull() to retrieve a stored session value and immediately remove it from the session in a single atomic operation.

When managing single-use session data (such as redirect URLs, temporary verification tokens, or step-by-step wizard state), reading the value and then manually calling `session()->forget('key')` is redundant.

The `session()->pull()` method combines `get()` and `forget()` into a single call.

## Basic Usage

```php
// Traditional two-step approach
$intendedUrl = session()->get('intended_url', '/dashboard');
session()->forget('intended_url');

// Concise atomic operation
$intendedUrl = session()->pull('intended_url', '/dashboard');
```

## Managing Multi-Step Form State

```php
public function completeCheckout()
{
    // Retrieve checkout payload and clear it so it cannot be re-submitted
    $orderData = session()->pull('checkout_payload');

    if (! $orderData) {
        return to_route('cart.index')->with('error', 'Session expired.');
    }

    $order = Order::create($orderData);
}
```

## Summary

- Returns the session value and removes the key from the session store immediately.
- Accepts an optional default fallback value as the second argument.
- Prevents stale state bugs in multi-step workflows and authentication redirects.
