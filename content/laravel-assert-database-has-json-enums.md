---
category: "Laravel"
tags: ["Laravel", "Testing", "Pest", "Enums"]
date: "2026-06-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Assert JSON Columns and Backed Enums with assertDatabaseHas

> In Laravel and Pest tests, assertDatabaseHas() natively queries nested JSON properties using arrow syntax and accepts backed Enum instances directly.

Testing JSON attributes or PHP backed enums requires no manual casting or encoding. assertDatabaseHas() supports arrow syntax and serializes enums automatically.

```php
$this->assertDatabaseHas('users', [
    'role' => UserRole::Maintainer,
    'settings->theme' => 'dark',
]);
```

- Handles Backed Enums directly without ->value casting
- Queries nested JSON keys using arrow notation
- Works out of the box with Pest and PHPUnit assertions
