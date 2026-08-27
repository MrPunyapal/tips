---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "SQL"]
date: "2025-01-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Execute Raw DDL and Schema Commands Safely with DB::statement()

> Use DB::statement() in database migrations to execute raw SQL statements for database triggers, stored procedures, and full-text indexes.

While Laravel's Schema Blueprint supports standard database types, advanced database-specific features (such as PostgreSQL full-text search dictionaries, MySQL generated columns, or custom triggers) require executing raw SQL.

`DB::statement()` executes raw SQL DDL statements directly against the active database connection.

## Executing Raw SQL in Migrations

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add a PostgreSQL GIN index for fast full-text searching
        DB::statement('CREATE INDEX posts_search_vector_idx ON posts USING gin(to_tsvector(\'english\', title || \' \' || body));');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS posts_search_vector_idx;');
    }
};
```

## Parameter Binding with DB::statement()

For parameterized DDL or maintenance operations:

```php
DB::statement('ALTER TABLE users AUTO_INCREMENT = :val', ['val' => 1000]);
```

## Summary

- Executes raw database DDL commands that Blueprint methods do not support.
- Supports parameter binding to prevent SQL injection.
- Returns a boolean indicating execution success.
