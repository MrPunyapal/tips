---
category: "Laravel"
tags: ["Laravel", "Testing", "Time"]
date: "2024-09-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Configuration"
---

# Ditch sleep() in Tests: Use Laravel Time Travel Helpers

> Use $this->travelTo() or freezeTime() in test suites to test date-sensitive logic without slowing down test execution with sleep().

Using sleep() in tests slows down execution suites significantly. Laravel's time travel helpers let you manipulate Carbon's internal clock instantaneously without real-world delays.

```php
test('trial expires after 14 days', function () {
    $user = User::factory()->create(['trial_ends_at' => now()->addDays(14)]);

    // Instantly jump 15 days into the future
    $this->travel(15)->days();

    expect($user->fresh()->hasExpiredTrial())->toBeTrue();
});
```

- Replaces slow real-time sleep() calls with instant mock clock jumps
- travel(15)->days() moves time forward dynamically
- freezeTime() locks current time to prevent test flickering
