---
category: "Laravel"
tags: ["Laravel", "Testing", "HTTP", "Security"]
date: "2023-09-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Catch Unmocked External API Calls with Http::preventStrayRequests()

> Enable Http::preventStrayRequests() in test suites to throw exceptions whenever HTTP requests are made without explicit mocks.

In test suites, accidental outbound HTTP requests to real third-party APIs (such as payment gateways, SMS providers, or webhooks) can trigger unwanted real-world charges or fail unpredictably without internet access.

Laravel provides `Http::preventStrayRequests()` to block all unmocked HTTP traffic.

## Enabling in Base TestCase

```php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Throw an exception if any HTTP request is unhandled by Http::fake()
        Http::preventStrayRequests();
    }
}
```

## What Happens on Stray Requests

If code executes an un-faked HTTP request during a test, Laravel immediately fails with a descriptive `RuntimeException`:

```text
Attempted request to [https://api.stripe.com/v1/charges] without a matching fake.
```

## Summary

- Guarantees 100% mocked external HTTP interactions in CI/CD pipelines.
- Prevents accidental calls to real production API endpoints during testing.
- Forces developers to declare explicit `Http::fake()` definitions for all outbound traffic.
