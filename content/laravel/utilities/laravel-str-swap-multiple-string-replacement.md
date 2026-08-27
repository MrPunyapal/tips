---
category: "Laravel"
tags: ["Laravel", "Strings", "Templates", "Utilities"]
date: "2023-04-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Perform Multiple String Replacements Simultaneously with Str::swap()

> Use Str::swap() to perform atomic multi-pattern string replacements using key-value translation dictionaries.

When generating template notifications, substituting placeholders in email bodies, or mapping abbreviations, chaining multiple `str_replace()` calls can cause earlier replacements to inadvertently corrupt later ones.

`Str::swap()` performs atomic multi-string replacements in a single operation.

## Basic Replacement Map

```php
use Illuminate\Support\Str;

$template = "Hello :name, your order :order_id is currently :status.";

$output = Str::swap([
    ':name'     => 'Punyapal',
    ':order_id' => '#4820',
    ':status'   => 'Processing',
], $template);

// Output: "Hello Punyapal, your order #4820 is currently Processing."
```

## Sanitizing Input Characters

```php
$slug = Str::swap([
    '&' => 'and',
    '@' => 'at',
    '%' => 'percent',
], 'Design & Code @ 100%');

// Output: "Design and Code at 100percent"
```

## Summary

- Replaces multiple key-value pairs in a single atomic pass.
- Prevents cascading replacement bugs caused by sequential `str_replace()` chains.
- Available as a static method (`Str::swap`) and fluent string helper (`str()->swap()`).
