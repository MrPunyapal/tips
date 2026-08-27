---
category: "Laravel"
tags: ["Laravel", "Strings", "Validation", "Utilities"]
date: "2025-03-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Validate and Detect URLs Cleanly with Str::isUrl()

> Use Str::isUrl() to determine whether a string is a valid URL with customizable protocol restrictions.

Checking if an input string is a valid web URL using custom regular expressions is error-prone and often mishandles edge cases (like Unicode domains, port numbers, or query parameters).

Laravel provides the native `Str::isUrl()` helper.

## Basic Usage

```php
use Illuminate\Support\Str;

Str::isUrl('https://mrpunyapal.dev'); // true
Str::isUrl('ftp://files.example.com'); // true
Str::isUrl('not-a-valid-url');        // false
```

## Restricting Allowed Protocols

Pass an array of allowed protocols to restrict valid URL schemes:

```php
// Only allow secure HTTPS URLs
$isSecure = Str::isUrl($input, ['https']);

// Allow HTTP or HTTPS only (rejects ftp://, javascript://, etc.)
$isWeb = Str::isUrl($input, ['http', 'https']);
```

## Fluent String Syntax

```php
if (str($request->website)->isUrl(['https'])) {
    // Process secure link
}
```

## Summary

- Uses robust native URL parsing under the hood.
- Protocol filtering prevents dangerous schemes like `javascript:` or `data:`.
- Replaces custom regex validation.
