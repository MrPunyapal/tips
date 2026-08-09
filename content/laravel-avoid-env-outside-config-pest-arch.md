---
category: "Laravel"
tags: ["Laravel", "Architecture", "Pest"]
date: "2023-11-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Never Use env() Outside Config Files + Enforce with Pest Arch

> env() returns null when config is cached in production. Always use config() and enforce this with Pest architecture tests.

In production environments running php artisan config:cache, calling env() outside files in config/*.php returns null. Enforce this rule across your codebase using Pest architecture testing.

```php
// tests/ArchitectureTest.php
arch('avoid env outside config')
    ->expect('env')
    ->not->toBeUsed()
    ->ignoring('config');
```

- env() returns null in production when config:cache is active
- Always define configuration keys in config/*.php files and call config('key')
- Pest arch tests catch leftover env() calls before deployment
