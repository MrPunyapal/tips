---
category: "PHP"
tags: ["PHP", "Security", "XSS"]
date: "2026-04-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Why strip_tags() Is Not Enough for XSS Protection

> strip_tags() removes HTML elements but fails to sanitize inline attributes or malformed HTML payload vectors. Use HTMLPurifier for rich text input.

A common security misconception in PHP is relying on strip_tags() to sanitize user-submitted rich text. It allows attribute payloads like onload= or javascript: URIs through if allowed tags are specified.

```php
use HTMLPurifier;

$purifier = new HTMLPurifier();
$cleanHtml = $purifier->purify($input);
```

- strip_tags() does not validate tag attributes or execution vectors
- Always escape plain text with e() or Blade's {{ $var }}
- For user-submitted rich text, use reliable HTML sanitizers like HTMLPurifier
