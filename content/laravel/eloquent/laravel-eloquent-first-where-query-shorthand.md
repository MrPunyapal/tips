---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2023-07-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Retrieve Single Matching Models with firstWhere()

> Replace where('column', $val)->first() chains with the concise firstWhere() query builder method.

Looking up a single model by email, slug, or token using `Model::where('email', $email)->first()` is one of the most frequent query patterns in Laravel applications.

The `firstWhere()` method combines `where()` and `first()` into a single call.

## Basic Usage

```php
use App\Models\User;

// Before: Verbose two-step query
$user = User::where('email', $email)->first();

// After: Clean single-method query
$user = User::firstWhere('email', $email);
```

## With Comparison Operators

```php
// Retrieve the first order over $1,000
$highValueOrder = Order::firstWhere('total_amount', '>', 1000);
```

## Works on Eloquent Collections Too

`firstWhere()` works identically on in-memory Eloquent and Support Collections:

```php
$users = User::all();

// Filters collection in memory without executing a new SQL query
$admin = $users->firstWhere('role', 'admin');
```

## Summary

- Shorthand for `->where(...)->first()`.
- Available on both Eloquent Query Builders and Collections.
- Supports operator arguments (`firstWhere('col', '>', $val)`).
