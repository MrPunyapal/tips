---
category: "Laravel"
tags: ["Laravel", "Exceptions", "Clean Code", "Error Handling"]
date: "2023-02-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Throw Exceptions Conditionally with throw_if() and throw_unless()

> Replace multi-line if statements with expressive throw_if() and throw_unless() helpers for guard clauses and business validations.

Writing repetitive `if ($condition) { throw new CustomException('...'); }` checks clutters controller actions and service classes.

Laravel provides the expressive `throw_if()` and `throw_unless()` helpers.

## Basic Guard Clauses

```php
use App\Exceptions\AccountSuspendedException;
use App\Exceptions\PaymentFailedException;

public function processOrder(User $user, Order $order)
{
    // Throws exception if condition evaluates to true
    throw_if($user->is_suspended, new AccountSuspendedException('Your account is suspended.'));

    // Throws exception if condition evaluates to false
    throw_unless($order->is_payable, PaymentFailedException::class, 'This order cannot be paid.');
}
```

## Passing Exception Classes and Messages

You can pass an instantiated exception object or the exception class name with a message:

```php
throw_if(
    $subscription->hasExpired(),
    SubscriptionExpiredException::class,
    'Your subscription expired on ' . $subscription->ends_at->format('M d')
);
```

## Summary

- `throw_if($bool, $exception, $message)` throws when condition is truthy.
- `throw_unless($bool, $exception, $message)` throws when condition is falsy.
- Simplifies domain guard clauses into single readable lines.
