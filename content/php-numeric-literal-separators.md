---
category: "PHP"
tags: ["PHP", "Syntax", "Readability"]
date: "2023-06-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Improve Large Number Readability with Numeric Literal Separators

> Use underscores (_) as visual numeric literal separators in PHP code to improve number readability.

Reading large numbers like 1000000000 requires counting zeroes manually. PHP allows underscores as visual separators inside numeric literals without affecting value evaluation.

```php
// Hard to read
$bytes = 1073741824;

// Clean and readable with numeric separators
$bytes = 1_073_741_824; // 1 GB
$price = 1_999_99;       // 1999.99 in cents
```

- Underscores serve purely as visual code separators and are ignored at runtime
- Works with integers, floats, binary (0b1010_0001), and hex (0xFF_00_FF)
- Prevents misreading large numbers during code reviews
