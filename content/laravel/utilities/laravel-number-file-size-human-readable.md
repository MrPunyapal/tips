---
category: "Laravel"
tags: ["Laravel", "Formatting", "Files", "Utilities"]
date: "2023-03-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Format Raw Bytes into Human-Readable File Sizes with Number::fileSize()

> Use Number::fileSize() to convert raw byte integers into formatted KB, MB, GB, and TB strings with customizable precision.

When displaying file upload sizes or storage quotas (such as `10485760` bytes), calculating power-of-1024 divisions manually with `log` math is tedious.

The `Number::fileSize()` helper converts raw byte counts into localized, readable file size strings.

## Basic Usage

```php
use Illuminate\Support\Number;

// Automatically resolves to MB
echo Number::fileSize(1024 * 1024 * 5);
// Output: "5 MB"

// Formats fractional bytes with custom precision
echo Number::fileSize(1572864, precision: 2);
// Output: "1.50 MB"

// Large file storage
echo Number::fileSize(1099511627776);
// Output: "1 TB"
```

## In Blade Templates

```blade
<div class="attachment-item">
    <span>{{ $document->name }}</span>
    <span class="text-muted">{{ Number::fileSize($document->size_in_bytes) }}</span>
</div>
```

## Summary

- Converts byte integers into B, KB, MB, GB, TB, and PB.
- Supports precision overrides via the `precision` argument.
- Cleanly replaces custom byte formatting helper functions.
