---
category: "Laravel"
tags: ["Laravel", "Strings", "Blade", "Utilities"]
date: "2023-05-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Truncate Long Text Cleanly with Str::words() and Str::limit()

> Use Str::words() to truncate text by word count and Str::limit() to truncate by character length with customizable ellipses.

When rendering blog card excerpts, search result snippets, or notification previews, cutting text with raw `substr()` cuts words in half.

Laravel provides word-aware and character-aware truncation helpers.

## Truncating by Word Count with Str::words()

```php
use Illuminate\Support\Str;

$body = "Laravel is a web application framework with expressive, elegant syntax.";

// Truncate to 5 words
echo Str::words($body, 5, '...');
// Output: "Laravel is a web application..."
```

## Truncating by Character Count with Str::limit()

```php
$title = "A Comprehensive Guide to Modern Eloquent Query Optimization in Laravel";

// Truncate to 40 characters without breaking words
echo Str::limit($title, 40, ' (read more)');
// Output: "A Comprehensive Guide to Modern Eloquent (read more)"
```

## In Blade Templates

```blade
<p class="summary">
    {{ str($post->body)->words(25) }}
</p>
```

## Summary

- `Str::words($string, $count, $end)` preserves complete words when truncating.
- `Str::limit($string, $length, $end)` truncates by character length.
- Appends customizable ellipsis markers (default: `...`).
