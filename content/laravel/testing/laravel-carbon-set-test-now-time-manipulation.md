---
category: "Laravel"
tags: ["Laravel", "Testing", "Carbon", "PHP"]
date: "2023-11-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Freeze and Inspect Application Time in Tests with Carbon setTestNow()

> Control system time during testing and development using Carbon::setTestNow() and inspect active time mocks with Carbon::hasTestNow().

When writing unit tests or developing features that rely on temporal calculations (such as subscription renewals, birthday promotions, trial expirations, or grace periods), relying on real system time creates flaky tests that fail depending on the time of day or month.

Carbon provides `setTestNow()` to lock time to a specific instant, alongside `hasTestNow()` to verify whether the temporal state is currently mocked.

## Freezing Time in Feature Logic

```php
use Carbon\Carbon;

function isBirthdayOfferActive(Carbon $birthday): bool
{
    return Carbon::now()->isSameDay($birthday);
}

// Check without mock time
$userBirthday = Carbon::create(1995, 11, 23);
isBirthdayOfferActive($userBirthday); // Evaluates against real current date

// Freeze application time to a specific target date
Carbon::setTestNow(Carbon::create(2026, 11, 23));

isBirthdayOfferActive($userBirthday); // Returns true!

// Reset back to real system time
Carbon::setTestNow(null);
```

## Checking if Mock Time is Active with `hasTestNow()`

In custom debug bars, health checks, or safety middleware, you can inspect whether the application is running under a simulated timestamp:

```php
use Carbon\Carbon;

if (Carbon::hasTestNow()) {
    logger()->warning('Application running with mocked Carbon time: ' . Carbon::now()->toDateTimeString());
}
```

## Laravel Test Helpers Under the Hood

Laravel's built-in testing helpers (`$this->travelTo()`, `$this->freezeTime()`) interact directly with `Carbon::setTestNow()`:

```php
test('trial expires after 14 days', function () {
    $this->freezeTime();

    $user = User::factory()->create(['trial_ends_at' => now()->addDays(14)]);
    expect($user->hasExpiredTrial())->toBeFalse();

    // Travel forward 15 days
    $this->travel(15)->days();

    expect($user->hasExpiredTrial())->toBeTrue();
});
```

## Summary

- Use `Carbon::setTestNow()` to freeze time during test executions and verify time-dependent edge cases reliably.
- Always reset mock time with `Carbon::setTestNow(null)` in test teardown hooks if not using Laravel's test time helpers.
- Use `Carbon::hasTestNow()` to check if time manipulation is currently active.
