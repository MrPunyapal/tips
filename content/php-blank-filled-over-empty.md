---
category: "PHP"
tags: ["PHP", "Laravel", "Best Practices"]
date: "2026-02-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Use blank() and filled() Instead of empty() in Laravel

> PHP's `empty()` treats `0`, `"0"`, and `false` as empty. Laravel's `blank()` and `filled()` helpers handle these values more intuitively and avoid silent logic bugs.

The `empty()` function in PHP is notoriously loose: it considers `0`, `"0"`, `false`, and even an empty array as "empty." This leads to subtle bugs when checking user input or configuration values.

Laravel provides `blank()` and `filled()` as safer, more expressive alternatives.

### Comparison table:

```php
// Value       | empty() | blank() | filled()
// 0           | true    | false   | true
// '0'         | true    | false   | true
// ''          | true    | true    | false
// ' '         | false   | true    | false  ← whitespace-only
// null        | true    | true    | false
// false       | true    | false   | true
// []          | true    | true    | false
```

### Practical usage:

```php
// ❌ Bug: rejects valid quantity of 0
if (empty($request->input('quantity'))) {
    return 'Quantity is required';
}

// ✅ Correct: 0 is a valid, non-blank value
if (blank($request->input('quantity'))) {
    return 'Quantity is required';
}

// Filter out blank values from a collection
$filtered = collect($data)->filter(fn ($v) => filled($v));
```

- `blank()` returns `true` for `null`, empty strings, and whitespace-only strings
- `filled()` is the inverse of `blank()`
- Both handle `0`, `false`, and `"0"` correctly: they are considered filled, not blank
