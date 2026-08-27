---
category: "Laravel"
tags: ["Laravel", "Testing", "Queue", "Clean Code"]
date: "2026-02-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Assert Background Job Dispatching with Queue::fake()

> Use Queue::fake() to prevent jobs from executing during tests and verify that background tasks are dispatched with correct parameters.

When testing controller actions that dispatch background queue jobs, executing real jobs slows down tests and triggers unwanted external side effects.

Laravel's `Queue::fake()` intercepts job dispatching and provides rich test assertions.

## Testing Job Dispatching

```php
use App\Jobs\ProcessSubscriptionPaymentJob;
use Illuminate\Support\Facades\Queue;

test('checkout dispatches payment job with correct amount', function () {
    Queue::fake();

    $response = $this->post('/checkout', [
        'plan_id' => 'pro_monthly',
    ]);

    $response->assertOk();

    // Assert that the job was dispatched to the queue
    Queue::assertPushed(ProcessSubscriptionPaymentJob::class, function ($job) {
        return $job->planId === 'pro_monthly';
    });

    // Assert that no other unexpected jobs were pushed
    Queue::assertPushed(ProcessSubscriptionPaymentJob::class, 1);
});
```

## Asserting Jobs Were Not Dispatched

```php
// Assert that a job was never dispatched under error conditions
Queue::assertNotPushed(ProcessSubscriptionPaymentJob::class);
```

## Summary

- Prevents background workers from running during test execution.
- Inspects dispatched job parameters using closure assertions.
- Provides assertions: `assertPushed()`, `assertNotPushed()`, `assertPushedOn()`.
