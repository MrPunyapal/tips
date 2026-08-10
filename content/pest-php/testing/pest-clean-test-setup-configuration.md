---
category: "Pest PHP"
tags: ["Pest", "Testing", "Setup"]
date: "2025-12-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Clean Up Pest Test Configuration in tests/Pest.php

> Organize base test case bindings, helper functions, and global traits cleanly inside tests/Pest.php.

Duplicate uses() declarations across every test file clutters test suites. Centralize common test traits (like RefreshDatabase or TestCase classes) inside tests/Pest.php grouped by directory.

```php
// tests/Pest.php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
uses(TestCase::class)->in('Unit');
```

- Centralizes global traits like RefreshDatabase for specific test folders
- Eliminates repetitive uses() imports across individual test files
- Defines custom expectation helpers globally for all test suites
