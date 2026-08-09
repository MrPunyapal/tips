---
category: "PHP"
tags: ["PHP","Syntax","Clean Code"]
date: "2025-11-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Simplify Default Values with Null Coalescing Assignment (??=)

> Replace verbose ternary checks and null coalescing reassignments with PHP null coalescing assignment operator (??=).

Setting default values on nullable variables often leads to repetitive variable references across statements.

PHP supports null coalescing assignment (`??=`) to combine evaluation and assignment into a single expression:

```php
$username = null;

// Verbose ternary operator
$username = $username !== null ? $username : 'MrPunyapal';

// Null coalescing operator
$username = $username ?? 'MrPunyapal';

// Clean null coalescing assignment
$username ??= 'MrPunyapal';
```

- Only assigns the right-hand value if the left-hand variable is `null`
- Prevents repeating variable names on both sides of the assignment
- Reduces code clutter in configuration arrays and option initialization
