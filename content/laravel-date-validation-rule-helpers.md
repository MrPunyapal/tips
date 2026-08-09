---
category: "Laravel"
tags: ["Laravel","Validation"]
date: "2025-12-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Use Fluent Date Validation Rule Helpers in Laravel 12.44

> Laravel 12.44 introduces fluent date validation helpers on the Rule facade, replacing string-based date comparisons with self-documenting method calls.

Validating dates relative to the current time previously required writing string rules like `date|before:now` or `date|after_or_equal:today`.

Laravel 12.44 adds readable method builders under `Rule::date()`:

```php
use Illuminate\Validation\Rule;

$request->validate([
    // Must be strictly in the past
    'logged_at' => [
        'required',
        Rule::date()->past(),
    ],

    // Must be strictly in the future
    'scheduled_at' => [
        Rule::date()->future(),
    ],

    // Must be current timestamp or past
    'completed_at' => [
        Rule::date()->nowOrPast(),
    ],

    // Must be current timestamp or future
    'expires_at' => [
        Rule::date()->nowOrFuture(),
    ],

    // Standard date-time format builder (Y-m-d H:i:s)
    'published_at' => [
        Rule::dateTime(),
    ],
]);
```

- Replaces hardcoded string rules with IDE-auto-completable methods
- `nowOrPast()` and `nowOrFuture()` handle inclusive boundary checks cleanly
- `Rule::dateTime()` standardizes database timestamp format validation
