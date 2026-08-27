---
category: "Laravel"
tags: ["Laravel", "Strings", "Utilities", "Clean Code"]
date: "2023-03-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Clean Up Redundant Whitespace with Str::squish()

> Use Str::squish() to remove duplicate spaces, tabs, and newlines from strings in a single call.

User-submitted text, pasted multi-line addresses, or scraped data frequently contains irregular whitespace, multiple consecutive spaces, and trailing newline characters.

Replacing irregular whitespace manually with complex regular expressions (`preg_replace('/\s+/', ' ', ...)`) is replaced by Laravel's `Str::squish()` method.

## Basic Usage

```php
use Illuminate\Support\Str;

$messyInput = "   Punyapal     Shah   

	  Developer  ";

// Strips all leading, trailing, and duplicate inner whitespace
$clean = Str::squish($messyInput);
// Output: "Punyapal Shah Developer"
```

## Using Fluent String Syntax

```php
$bio = str($request->input('bio'))->squish()->value();
```

## Practical Form Request Cleaning

Pair with `prepareForValidation()` to sanitize search keywords:

```php
protected function prepareForValidation(): void
{
    if ($this->has('query')) {
        $this->merge([
            'query' => str($this->query)->squish()->value(),
        ]);
    }
}
```

## Summary

- Converts multiple consecutive spaces, tabs, and newlines into a single space.
- Automatically trims leading and trailing whitespace.
- Available as a static helper (`Str::squish`) and fluent string method (`str()->squish()`).
