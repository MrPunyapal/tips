---
category: "Laravel"
tags: ["Laravel", "Pint", "Code Quality"]
date: "2025-08-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Automate Short Closure Conversions with Laravel Pint

> Enable the use_arrow_functions rule in pint.json to automatically refactor single-line closures into arrow functions.

Manually converting single-line `function () use ($var)` callbacks to `fn ()` arrow functions across an entire project takes time.

Laravel Pint's `use_arrow_functions` rule automates this refactoring across your codebase.

---

## Configuration

Enable the rule in your `pint.json`:

```json
{
    "rules": {
        "use_arrow_functions": true
    }
}
```

---

## What It Automatically Refactors

```php
// Before Pint:
$active = array_filter($users, function ($user) use ($role) {
    return $user->hasRole($role);
});

// After Pint:
$active = array_filter($users, fn ($user) => $user->hasRole($role));
```

---

## Key Benefits

- **No Manual `use` Bindings**: Arrow functions automatically capture variables from the parent scope by value.
- **Consistent Syntax**: Enforces modern PHP short closure syntax across all collection pipelines, array functions, and service providers.
- **Safe Transformation**: Applies only to single-line return statements; complex multi-line closures remain untouched.
