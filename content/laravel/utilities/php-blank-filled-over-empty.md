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

PHP's empty() is notoriously loose: it considers 0 and '0' empty. Laravel provides blank() and filled() as safer alternatives.

```php
if (blank($request->input('quantity'))) {
    return 'Quantity is required';
}
```

- blank() treats 0 and '0' as filled values, not blank
- filled() is the exact inverse of blank()
- Handles whitespace-only strings correctly
