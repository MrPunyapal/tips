---
category: "Laravel"
tags: ["Laravel", "Strings", "Data Cleansing", "Utilities"]
date: "2025-07-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Collapse Consecutive Duplicate Characters with Str::deduplicate()

> Use Str::deduplicate() to collapse repeated consecutive spaces, slashes, or characters into a single instance.

When sanitizing user input, formatting URI paths, or cleaning multi-line text, users frequently enter repeated spaces or duplicate slashes (e.g. `path//to///resource` or `hello    world`).

`Str::deduplicate()` replaces consecutive instances of a character with a single instance.

## Cleaning Consecutive Spaces

```php
use Illuminate\Support\Str;

$text = "This   has   too    many   spaces.";

echo Str::deduplicate($text);
// Output: "This has too many spaces."
```

## Cleaning Duplicate URL Slashes

Pass the character to deduplicate as the second argument:

```php
$path = "api///v1//users////profile";

echo Str::deduplicate($path, '/');
// Output: "api/v1/users/profile"
```

## Summary

- Collapses repeated consecutive characters into a single character.
- Defaults to space characters if no second argument is provided.
- Available as a static method and via `str()->deduplicate()`.
