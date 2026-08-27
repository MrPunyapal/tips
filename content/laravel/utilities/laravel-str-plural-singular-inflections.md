---
category: "Laravel"
tags: ["Laravel", "Strings", "Utilities", "Clean Code"]
date: "2023-11-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Handle English Pluralization and Word Inflections with Str::plural()

> Use Str::plural() and Str::singular() to handle singular and plural word forms dynamically based on counts and UI contexts.

When displaying dynamic item counts in user interfaces (such as `"1 item"` vs `"5 items"`, or `"1 match"` vs `"0 matches"`), writing manual ternary string checks (`$count === 1 ? 'item' : 'items'`) is verbose.

Laravel provides english inflection helpers via `Str::plural()` and `Str::singular()`.

## Dynamic Pluralization Based on Count

```php
use Illuminate\Support\Str;

$count = 1;
echo $count . ' ' . Str::plural('comment', $count);
// Output: "1 comment"

$count = 3;
echo $count . ' ' . Str::plural('comment', $count);
// Output: "3 comments"
```

## Handling Irregular Plurals

Laravel handles complex irregular English words automatically:

```php
echo Str::plural('child');    // "children"
echo Str::plural('person');   // "people"
echo Str::plural('analysis'); // "analyses"
echo Str::plural('criterion');// "criteria"
```

## Converting to Singular

```php
echo Str::singular('categories'); // "category"
echo Str::singular('wolves');     // "wolf"
```

## Using Fluent String Syntax

```php
echo str('task')->plural($tasks->count());
```

## Summary

- Automatically inflects regular and irregular English words.
- Second argument accepts item counts to determine singular or plural form.
- Keeps Blade views and notification templates clean and natural.
