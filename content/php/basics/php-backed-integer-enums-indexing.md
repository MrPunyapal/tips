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

In PHP, 0 evaluates as false-y in loose condition checks like empty() or if ($enumValue). Starting integer enum values at 1 prevents accidental false-y evaluation bugs.

```php
// BAD: Case 0 is false-y in loose checks!
enum Priority: int
{
    case Low = 0;
    case Medium = 1;
    case High = 2;
}

// GOOD: Start indexing at 1
enum Priority: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
}
```

- Prevents false-y evaluation pitfalls when checking enum integer values in loose conditionals
- Aligns with standard 1-based database primary key indexing conventions
- makes sure empty() checks on enum raw values behave predictably
