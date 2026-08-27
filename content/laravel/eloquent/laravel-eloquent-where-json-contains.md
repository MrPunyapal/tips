---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "JSON"]
date: "2023-11-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Query JSON Arrays and Columns with whereJsonContains()

> Query JSON columns and array attributes efficiently using whereJsonContains() and whereJsonLength() in Eloquent.

When storing JSON columns in MySQL, PostgreSQL, or SQLite (such as user preferences, tag arrays, or metadata payloads), filtering rows that contain a specific array value is supported natively by Eloquent.

## Querying JSON Arrays

```php
use App\Models\User;

// Finds users who have 'admin' in their JSON 'roles' array column
$admins = User::whereJsonContains('roles', 'admin')->get();
```

## Querying Multiple JSON Values

Pass an array to find rows that contain all or any of the specified values:

```php
// User must have both 'editor' AND 'publisher'
$editors = User::whereJsonContains('roles', ['editor', 'publisher'])->get();

// User must have either 'editor' OR 'publisher'
$staff = User::whereJsonContainsKey('roles', 'editor')
    ->orWhereJsonContains('roles', 'publisher')
    ->get();
```

## Querying Nested JSON Object Attributes

```php
// Query nested key: { "settings": { "theme": "dark" } }
$darkThemeUsers = User::where('settings->theme', 'dark')->get();
```

## Querying Array Length with whereJsonLength()

```php
// Finds users with 3 or more configured roles
$multiRoleUsers = User::whereJsonLength('roles', '>=', 3)->get();
```

## Summary

- Uses native database JSON functions (`JSON_CONTAINS`, `jsonb_exists`).
- Works on both simple JSON arrays and nested object dictionaries.
- Supports length comparisons via `whereJsonLength()`.
