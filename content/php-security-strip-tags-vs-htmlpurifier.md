---
category: "PHP"
tags: ["PHP", "Security", "XSS"]
date: "2026-04-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Why strip_tags() Is Not Enough for XSS Protection

> `strip_tags()` removes HTML elements but fails to sanitize inline attributes or malformed HTML payload vectors. Use HTMLPurifier for rich text input sanitization.

A common security misconception in PHP is relying on `strip_tags()` to sanitize user-submitted rich text or HTML.

While `strip_tags()` strips XML/HTML tags, it allows attribute payloads like `onload=`, `onerror=`, or `javascript:` URIs through if allowed tags are specified.

### ❌ Insecure: strip_tags with allowed tags bypass

```php
// User inputs malicious attribute inside allowed tag
$input = '<img src="invalid" onerror="alert(document.cookie)">';

// strip_tags allows <img> but retains the malicious onerror payload!
$sanitized = strip_tags($input, '<img>');
// Output: <img src="invalid" onerror="alert(document.cookie)">
```

### ✅ Secure: HTMLPurifier cleans attributes and malformed markup

```php
use HTMLPurifier;
use HTMLPurifier_Config;

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

// Safely strips malicious attributes while preserving allowed HTML
$cleanHtml = $purifier->purify($input);
```

- `strip_tags()` does not validate tag attributes or prevent JavaScript execution vectors
- Always escape plain text with `e()` or Blade's `{{ $var }}`
- For user-submitted rich text, use robust HTML sanitizers like HTMLPurifier
