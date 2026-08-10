---
category: "PHP"
tags: ["PHP", "Rector", "Refactoring"]
date: "2026-07-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Automate PHP Readonly Class Refactoring with Rector

> Use Rector rules to automatically convert immutable DTOs and value objects into native PHP 8.2 readonly classes.

Manually adding readonly keywords across dozens of data transfer objects is repetitive. Rector automates upgrading class properties and class declarations across codebase suites.

```php
// rector.php
use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;

return RectorConfig::configure()
    ->withRules([
        ReadOnlyClassRector::class,
    ]);
```

- Converts immutable classes to native PHP 8.2 readonly class declarations
- Enforces immutability at compiler level for all class properties
- Eliminates boilerplate docblock annotations and manual checks
