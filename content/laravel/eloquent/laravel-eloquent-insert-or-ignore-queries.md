---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance"]
date: "2023-07-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Batch Insert Records Safely with insertOrIgnore()

> Use insertOrIgnore() to perform mass database insertions while silently ignoring unique constraint violations and duplicate key errors.

When batch-importing large datasets or synchronizing logs with unique constraints, a single duplicate row causes the entire standard `insert()` query to fail with a database exception.

`insertOrIgnore()` compiles `INSERT IGNORE` (MySQL) or `INSERT ... ON CONFLICT DO NOTHING` (PostgreSQL/SQLite).

## Bulk Inserting with Conflict Suppression

```php
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

DB::table('tags')->insertOrIgnore([
    ['name' => 'Laravel',    'slug' => 'laravel'],
    ['name' => 'PHP',        'slug' => 'php'],
    ['name' => 'Laravel',    'slug' => 'laravel'], // Duplicate: ignored silently
    ['name' => 'JavaScript', 'slug' => 'javascript'],
]);
```

## Using on Eloquent Models

```php
use App\Models\UserRole;

UserRole::insertOrIgnore([
    ['user_id' => 1, 'role_id' => 2],
    ['user_id' => 1, 'role_id' => 3],
]);
```

## Summary

- Suppresses primary key and unique constraint collision errors during mass inserts.
- Inserts valid new records without aborting the entire batch.
- Supported across MySQL, PostgreSQL, and SQLite database drivers.
