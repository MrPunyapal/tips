---
category: "Laravel"
tags: ["Laravel", "Database", "Query Builder", "MySQL", "MariaDB"]
date: "2026-08-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Precise Binary String Comparisons with whereBinary() in Laravel 13.27

> Laravel 13.27 adds whereBinary() to the query builder, replacing manual whereRaw() expressions for exact byte-level string comparisons.

When querying case-sensitive fields (such as invite codes, usernames, or token hashes) on MySQL/MariaDB tables with case-insensitive (`_ci`) collations, normal `where()` queries treat uppercase and lowercase strings identically.

To enforce exact byte matching, developers previously had to drop down to raw SQL.

Laravel 13.27 introduces native query builder methods for binary comparisons.

---

## Before Laravel 13.27

```php
use Illuminate\Support\Facades\DB;

$user = DB::table('users')
    ->whereRaw('username = BINARY ?', [$username])
    ->first();
```

---

## Laravel 13.27: whereBinary()

```php
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Query builder
$user = DB::table('users')
    ->whereBinary('username', $username)
    ->first();

// Eloquent model
$invitation = Invitation::whereBinary('code', $code)->first();
```

---

## Additional Binary Query Methods

Laravel 13.27 also includes the corresponding OR and negation variants:

```php
// OR condition
$user = DB::table('users')
    ->where('status', 'active')
    ->orWhereBinary('handle', $handle)
    ->first();

// Negation (NOT BINARY)
$tokens = DB::table('api_tokens')
    ->whereNotBinary('secret_hash', $revokedHash)
    ->get();

// Negated OR
$query->orWhereNotBinary('token', $expiredToken);
```

---

## Summary

- `whereBinary()` provides first-class binary string comparisons on MySQL and MariaDB.
- Avoids writing raw `BINARY ?` SQL strings in `whereRaw()`.
- Includes `orWhereBinary()`, `whereNotBinary()`, and `orWhereNotBinary()`.
