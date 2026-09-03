---
category: "PHP"
tags: ["PHP", "Enums", "Best Practices"]
date: "2024-08-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Basics"
---

# Integer Enums: Start Indexing from 1, Avoid 0

> When creating integer-backed PHP Enums, index starting from 1 to avoid false-y evaluation bugs in loose comparisons.

In PHP, `0` evaluates as false-y in loose condition checks like `empty()` or `if ($enum->value)`. Starting integer enum values at `1` prevents accidental false-y evaluation bugs.

---

## Before & After

```php
// ❌ Dangerous: 0 evaluates to false in loose checks
enum Priority: int
{
    case Low = 0;
    case Medium = 1;
    case High = 2;
}

// empty(Priority::Low->value) is true!

// ✅ Recommended: Start indexing at 1
enum Priority: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
}

// empty(Priority::Low->value) is false!
```

---

## Key Benefits

- **Prevents False-y Pitfalls**: Avoids unexpected truthy/falsy evaluation bugs when inspecting raw enum integer values.
- **Database Consistency**: Aligns naturally with standard 1-based auto-incrementing database ID conventions.
- **Predictable Empty Checks**: Ensures `empty()` or `blank()` checks on enum values behave intuitively without silent failures.
