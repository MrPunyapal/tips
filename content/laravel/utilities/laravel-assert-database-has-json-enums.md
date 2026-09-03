---
category: "Laravel"
tags: ["Laravel", "Testing", "Pest", "Enums"]
date: "2026-06-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Assert JSON Columns and Backed Enums with assertDatabaseHas

> In Laravel and Pest tests, assertDatabaseHas() natively queries nested JSON properties using arrow syntax and accepts backed Enum instances directly.

Testing JSON attributes or PHP backed enums in database assertions requires no manual casting, JSON encoding, or `->value` calls.

`assertDatabaseHas()` natively serializes backed enums and translates arrow notation into database JSON queries.

---

## Code Examples

```php
use App\Enums\UserRole;

// In Pest tests:
assertDatabaseHas('users', [
    'role' => UserRole::Maintainer, // Serializes backed enum automatically
    'settings->theme' => 'dark',    // Queries nested JSON key
    'settings->notifications->email' => true,
]);

// In PHPUnit tests:
$this->assertDatabaseHas('users', [
    'role' => UserRole::Maintainer,
    'settings->theme' => 'dark',
]);
```

---

## Key Benefits

- **Enum Serialization**: Passes backed enum instances directly without manual `->value` extraction.
- **Nested JSON Traversal**: Arrow syntax (`settings->theme`) queries database JSON paths across MySQL, PostgreSQL, and SQLite.
- **Framework Agnostic**: Works identically in both Pest test functions and PHPUnit test cases.
