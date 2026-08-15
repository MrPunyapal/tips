---
category: "Laravel"
tags: ["Laravel", "Database", "Seeders"]
date: "2026-01-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Load Large SQL Dumps in Laravel Seeders

> Use DB::getSchemaState()->load() to load large reference SQL dump files in seeders, with DB::unprepared(file_get_contents(...)) as a fallback for older Laravel versions.

When seeding large static reference datasets such as countries, states, cities, or timezones, writing thousands of individual Eloquent `create()` or `insert()` calls adds unnecessary overhead. Developers frequently store these datasets as raw `.sql` files inside their repository and load them during database seeding.

## The Common Approach

A standard way to execute an external SQL file is reading its contents into PHP memory and executing the raw query buffer with `DB::unprepared()`:

```php
use Illuminate\Support\Facades\DB;

DB::unprepared(
    file_get_contents(database_path('seeders/sql/world.sql'))
);
```

This approach works by having `file_get_contents()` read the entire SQL dump into a PHP string variable, which is then passed to PDO via `DB::unprepared()`. For very large SQL files, loading the entire file into PHP memory first can consume significant memory allocations.

## Prefer SchemaState When Available

In Laravel versions that support schema state inspection, you can load the SQL file using `DB::getSchemaState()->load()`:

```php
use Illuminate\Support\Facades\DB;

DB::getSchemaState()->load(
    database_path('seeders/sql/world.sql')
);
```

Instead of requiring application code to read the complete SQL file into a PHP string in memory, `getSchemaState()->load()` delegates the file path directly to Laravel's database-specific schema state implementation.

## Older Laravel Projects

For projects running on older Laravel versions where `DB::getSchemaState()->load()` is not available, `DB::unprepared(file_get_contents(...))` remains a reliable and practical solution. There is no need to restructure working seeders on older installations unless you encounter memory limits during data loading.

## Example Seeder

Here is how a dedicated reference dataset seeder looks using the preferred approach:

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorldSeeder extends Seeder
{
    public function run(): void
    {
        DB::getSchemaState()->load(
            database_path('seeders/sql/world.sql')
        );
    }
}
```

If your project requires compatibility with older Laravel releases, the fallback pattern can be used inside the same seeder:

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorldSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared(
            file_get_contents(
                database_path('seeders/sql/world.sql')
            )
        );
    }
}
```
