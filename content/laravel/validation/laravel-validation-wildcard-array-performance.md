---
category: "Laravel"
tags: ["Laravel","Validation","Performance"]
date: "2026-08-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Optimize Large Wildcard Array Validation in Laravel 13.24

> Laravel 13.24 introduces dramatic performance optimizations for validating large nested arrays with wildcard rules, reducing validation execution time from 85 seconds to under 1 second.

When validating large payloads containing thousands of array items using wildcard rules (such as `items.*.name`), earlier Laravel versions spent significant time matching nested array keys recursively.

Laravel 13.24 optimizes wildcard rule compilation and key evaluation under the hood:

```php
use Illuminate\Support\Facades\Validator;

$rules = [
    'items' => ['array'],
    'items.*.name' => ['nullable', 'string'],
    'items.*.email' => ['nullable', 'email'],
    'items.*.phone' => ['nullable', 'string'],
    'items.*.address' => ['nullable', 'string'],
];

$data = [
    'items' => array_fill(0, 8_000, [
        'name' => 'John',
        'email' => 'john@example.com',
    ]),
];

// Before Laravel 13.24: ~85 seconds
// In Laravel 13.24+: ~1 second
Validator::make($data, $rules)->passes();
```

- Wildcard array validation on 8,000+ items is over 80x faster
- Requires zero code changes in your existing FormRequests or Validator calls
- Essential optimization for bulk API imports and data synchronization endpoints
