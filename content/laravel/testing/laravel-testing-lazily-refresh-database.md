---
category: "Laravel"
tags: ["Laravel", "Testing", "Performance", "Database"]
date: "2023-10-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Speed Up Test Suites with the LazilyRefreshDatabase Trait

> Use LazilyRefreshDatabase instead of RefreshDatabase to run database transactions only for tests that actually touch the database.

When running large test suites, the traditional `RefreshDatabase` trait migrates and resets database transactions before every test, even for pure unit tests that only test formatting or calculations without touching SQL.

`LazilyRefreshDatabase` defers database initialization until a query is executed.

## Using LazilyRefreshDatabase in Tests

```php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
    use LazilyRefreshDatabase;

    test('calculates user discount percentage', function () {
        // Pure calculation: No database query executed, database setup is skipped!
        $discount = (new Calculator)->discount(100, 20);
        expect($discount)->toBe(80);
    });

    test('creates user record in database', function () {
        // First database query triggers lazy transaction setup automatically
        $user = User::factory()->create();
        $this->assertModelExists($user);
    });
}
```

## Summary

- Defer database connection setup until the first SQL query executes.
- Significantly accelerates test suite runtime in hybrid unit/feature suites.
- Drop-in replacement for `RefreshDatabase`.
