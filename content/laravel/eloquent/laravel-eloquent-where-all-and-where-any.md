---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Queries"]
date: "2023-08-02"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Search Across Multiple Columns with whereAny() and whereAll()

> Use whereAny() and whereAll() to apply query conditions across multiple database columns without repetitive orWhere() closures.

When building search features that match a query string against first name, last name, email, and company, writing nested `where()` and `orWhere()` blocks is verbose and prone to operator precedence bugs.

Laravel provides `whereAny()` and `whereAll()`.

## Searching Across Any Column with whereAny()

```php
use App\Models\User;

$search = 'punyapal';

// Generates: WHERE (first_name LIKE '%punyapal%' OR last_name LIKE '%punyapal%' OR email LIKE '%punyapal%')
$users = User::whereAny(
    ['first_name', 'last_name', 'email', 'username'],
    'LIKE',
    "%{$search}%"
)->get();
```

## Enforcing Constraints on All Columns with whereAll()

```php
// Generates: WHERE (rating > 4 AND score > 4 AND quality_index > 4)
$eliteProducts = Product::whereAll(
    ['rating', 'score', 'quality_index'],
    '>',
    4
)->get();
```

## Summary

- Automatically wraps conditions in parentheses to preserve logical SQL operator precedence.
- `whereAny([...], '=', $val)` combines columns with `OR`.
- `whereAll([...], '=', $val)` combines columns with `AND`.
