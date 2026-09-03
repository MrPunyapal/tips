---
category: "Laravel"
tags: ["Laravel", "PHP", "Helpers", "Best Practices"]
date: "2026-02-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Use blank() and filled() Instead of empty() in Laravel

> PHP's empty() treats 0, '0', and false as empty. Laravel's blank() and filled() helpers handle these values intuitively without silent bugs.

PHP's native `empty()` is notoriously loose: it considers `0`, `'0'`, and `false` as empty, causing subtle bugs when validating quantities, scores, or boolean toggles.

Laravel provides `blank()` and `filled()` as intuitive, string-trimmed alternatives.

---

## Comparison Matrix

```php
// PHP empty() pitfalls:
empty(0);       // true  (oops: 0 is a valid quantity!)
empty('0');     // true  (oops: '0' is a valid string!)
empty('   ');   // false (whitespace is NOT considered empty by PHP)

// Laravel blank():
blank(0);       // false (0 is a real value)
blank('0');     // false ('0' is a real value)
blank('   ');   // true  (automatically trims whitespace)
blank('');      // true
blank(null);    // true
blank([]);      // true

// Laravel filled() (exact inverse of blank()):
filled(0);      // true
filled('hello');// true
filled('   ');  // false
```

---

## Usage in Controllers

```php
// Clean validation without false positives on 0
if (blank($request->input('score'))) {
    return response()->json(['error' => 'Score is required'], 422);
}
```
