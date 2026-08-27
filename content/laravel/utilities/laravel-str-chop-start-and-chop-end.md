---
category: "Laravel"
tags: ["Laravel", "Strings", "Utilities", "Clean Code"]
date: "2023-04-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Strip Specific Prefixes and Suffixes with Str::chopStart() and chopEnd()

> Use Str::chopStart() and Str::chopEnd() to remove specific leading or trailing substrings without regex or manual length slicing.

When sanitizing URLs, removing `https://` protocols, stripping namespace prefixes, or cleaning file extensions, `ltrim` and `rtrim` strip individual characters rather than exact substrings, often corrupting text.

Laravel provides `Str::chopStart()` and `Str::chopEnd()` to remove exact prefix and suffix matches.

## Stripping Leading Prefixes with chopStart()

```php
use Illuminate\Support\Str;

$url = 'https://mrpunyapal.dev';

// Strips 'https://' from the beginning
echo Str::chopStart($url, 'https://');
// Output: "mrpunyapal.dev"

// Accepts an array of potential prefixes
echo Str::chopStart('http://laravel.com', ['https://', 'http://']);
// Output: "laravel.com"
```

## Stripping Trailing Suffixes with chopEnd()

```php
$filename = 'invoice-report.blade.php';

// Strips exact '.blade.php' extension
echo Str::chopEnd($filename, '.blade.php');
// Output: "invoice-report"
```

## Using Fluent String Syntax

```php
$cleanPath = str($request->path())->chopStart('api/v1/')->value();
```

## Summary

- Removes exact substrings from the start or end of a string.
- Accepts arrays of candidate prefixes and suffixes.
- Safe alternative to `ltrim()` and `rtrim()`.
