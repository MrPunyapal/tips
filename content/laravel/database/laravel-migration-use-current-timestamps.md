---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "MySQL"]
date: "2023-10-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Set Database Default Timestamps with useCurrent() and useCurrentOnUpdate()

> Use useCurrent() and useCurrentOnUpdate() in migrations to let the database engine manage default timestamps automatically.

When inserting records via raw SQL queries, third-party integrations, or bulk inserts where Eloquent timestamp events do not fire, columns without defaults can fail or insert `NULL`.

Laravel migrations provide `useCurrent()` and `useCurrentOnUpdate()` to configure database-level default timestamps.

## Using Database-Level Timestamps in Migrations

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event');

            // Sets DEFAULT CURRENT_TIMESTAMP in database
            $table->timestamp('created_at')->useCurrent();

            // Sets DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP (MySQL/MariaDB)
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }
};
```

## Custom Event Timestamps

```php
$table->timestamp('purchased_at')->useCurrent();
```

## Summary

- Enforces timestamp defaults at the database engine level.
- Guarantees valid timestamps even when bypassing Eloquent models.
- `useCurrentOnUpdate()` automatically refreshes timestamps on MySQL/MariaDB row updates.
