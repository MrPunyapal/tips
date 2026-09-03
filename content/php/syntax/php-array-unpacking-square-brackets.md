---
category: "PHP"
tags: ["PHP", "Arrays", "Syntax"]
date: "2025-12-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Syntax"
---

# Unpack Arrays with Spread Syntax in PHP 8.1

> Use array unpacking (...) inside square bracket array literals for string-keyed and indexed array merging.

PHP 8.1 expanded array unpacking to support string keys inside square bracket array literals, replacing verbose `array_merge()` calls with clean spread syntax.

---

## Code Example

```php
$defaults = [
    'theme'         => 'dark',
    'notifications' => true,
];

$userCustom = [
    'notifications' => false,
    'language'      => 'en',
];

// Unpacks and merges arrays natively (later values overwrite earlier keys)
$options = [...$defaults, ...$userCustom];

// Result:
// [
//     'theme'         => 'dark',
//     'notifications' => false,
//     'language'      => 'en',
// ]
```

---

## Key Benefits

- **Clean Syntax**: Replaces verbose `array_merge()` function calls with native spread operator syntax.
- **String Key Support**: In PHP 8.1+, string keys are fully supported; conflicting keys are overwritten by later elements in the list.
- **Array Composition**: Ideal for merging configuration defaults, request payloads, and query parameters cleanly.
