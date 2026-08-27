---
category: "Laravel"
tags: ["Laravel", "Testing", "Utilities", "Clean Code"]
date: "2023-03-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Test Time Delays Without Slowing Down Tests Using the Sleep Helper

> Replace PHP's native sleep() and usleep() with Laravel's Sleep facade to write testable, fakeable time pauses.

Using native `sleep(5)` in queue jobs or API polling loops pauses real system execution, causing test suites to run slowly.

Laravel provides the `Illuminate\Support\Sleep` facade, which supports inspection and faking during tests.

## Using Sleep in Application Code

```php
use Illuminate\Support\Sleep;

// Pause for 5 seconds
Sleep::for(5)->seconds();

// Pause for 500 milliseconds
Sleep::for(500)->milliseconds();

// Pause until a specific Carbon timestamp
Sleep::until(now()->addMinutes(2));
```

## Faking Delays in Tests

In unit and feature tests, call `Sleep::fake()` to bypass real sleep delays and assert that pauses occurred:

```php
use Illuminate\Support\Sleep;

test('it pauses before retrying failed requests', function () {
    // Prevent real sleep delays in tests
    Sleep::fake();

    $service = new PaymentGateway();
    $service->chargeWithRetry($order);

    // Verify sleep occurred without waiting 10 seconds!
    Sleep::assertSlept(fn ($duration) => $duration->totalSeconds === 10);
});
```

## Summary

- Replaces native `sleep()` and `usleep()` with expressive fluent units (`seconds()`, `milliseconds()`).
- `Sleep::fake()` eliminates execution pauses in test suites.
- Provides assertions like `assertSlept()` and `assertNeverSlept()`.
