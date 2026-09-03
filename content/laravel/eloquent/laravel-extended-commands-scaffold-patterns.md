---
category: "Laravel"
tags: ["Laravel", "Artisan", "DX"]
date: "2026-06-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Scaffold Actions, Builders, and Collections with Artisan

> Extend your generator commands to scaffold common domain patterns like Actions, Custom Query Builders, and Collections directly via Artisan.

Pushing domain logic into single-purpose Action classes or custom Eloquent Query Builders keeps controllers and models lean.

Using `mrpunyapal/laravel-extended-commands`, you can scaffold these patterns directly from the CLI.

---

## Installation & CLI Usage

```bash
composer require --dev mrpunyapal/laravel-extended-commands

# Scaffold a custom Eloquent Query Builder
php artisan make:builder UserBuilder

# Scaffold a single-action class
php artisan make:action CreateOrderAction

# Scaffold a custom Eloquent Collection
php artisan make:collection OrderCollection
```

---

## Why Use Dedicated Generators

- **Custom Builders**: Encapsulates complex query scopes into a dedicated builder class (`app/Builders/UserBuilder.php`) instead of bloating the Model.
- **Action Classes**: Generates invokable, single-responsibility classes (`app/Actions/CreateOrderAction.php`) for business transactions.
- **Team Consistency**: Enforces predictable directory structures and namespaces across the entire development team.
