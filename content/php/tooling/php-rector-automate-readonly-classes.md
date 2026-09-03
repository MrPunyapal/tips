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

Manually adding `readonly` keywords across dozens of data transfer objects (DTOs) and value objects is repetitive.

Rector automates upgrading class declarations across your codebase using AST refactoring.

---

## Configuration

Register `ReadOnlyClassRector` in `rector.php`:

```php
// rector.php
use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;

return RectorConfig::configure()
    ->withRules([
        ReadOnlyClassRector::class,
    ]);
```

---

## What It Refactors

```php
// Before Rector (individual readonly properties):
class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {}
}

// After Rector (PHP 8.2+ native readonly class):
readonly class UserData
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
```

---

## Key Benefits

- **Compiler-Level Immutability**: Enforces that all properties cannot be modified after instantiation.
- **Cleaner Constructors**: Removes repetitive `readonly` property declarations in favor of a single class-level keyword.
- **Safe Automation**: Rector verifies property usage before promoting classes, ensuring mutable entities are not broken.
