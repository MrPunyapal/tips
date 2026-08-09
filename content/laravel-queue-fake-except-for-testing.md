---
category: "Laravel"
tags: ["Laravel","Testing","Pest PHP"]
date: "2025-06-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Selectively Fake Jobs in Tests with Queue::fakeExceptFor()

> Use Queue::fakeExceptFor() to execute specific background jobs synchronously during a test while preventing all other jobs from running.

When testing a feature that dispatches multiple background jobs, using `Queue::fake()` prevents all jobs from executing. If your test relies on a critical job running inline, total faking breaks test setup.

`Queue::fakeExceptFor()` lets you specify jobs that should execute normally while faking the rest:

```php
use App\Jobs\CriticalSystemJob;
use App\Jobs\EmailNotification;
use Illuminate\Support\Facades\Queue;

test('queue dispatches email while executing critical system job inline', function () {
    Queue::fakeExceptFor(function () {
        Queue::push(new CriticalSystemJob); // Executes inline
        Queue::push(new EmailNotification);  // Faked

        // Faked jobs are tracked by assertion helpers
        Queue::assertPushed(EmailNotification::class);
    }, [CriticalSystemJob::class]);
});
```

- Allows real execution for essential side effects during integration tests
- Keeps unneeded jobs (emails, webhooks) isolated and faked
- Also available on `Event::fakeExceptFor()` and `Bus::fakeExceptFor()`
