---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX"]
date: "2025-06-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Focus Test Runs Instantly with Pest ->only() Method

> Append ->only() to any Pest test declaration to execute only that specific test without writing long CLI filter strings.

Filtering specific test names via CLI flags like --filter requires typing exact strings. Appending ->only() to a test declaration silences all other tests in the file.

```php
test('calculates order total with discounts', function () {
    expect(true)->toBeTrue();
})->only(); // Only this test will run!

test('another test', function () {
    // Skipped
});
```

- Focuses test runner execution strictly on tagged test cases
- Avoids typing long CLI --filter flags during debugging sessions
- Remember to remove ->only() before committing changes
