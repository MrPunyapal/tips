---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "MySQL"]
date: "2023-11-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Organize Database Columns with the after() Migration Modifier

> Position new database columns logically in existing tables using ->after('column_name') and ->first() in MySQL and MariaDB migrations.

When adding new columns to existing database tables, database engines place new columns at the very end of the table by default. Over time, related columns (such as `address_line2` or `billing_city`) become scattered across the schema.

Laravel schema builder provides `->after()` and `->first()` to position columns in supported database engines (MySQL and MariaDB).

## Positioning Columns After an Existing Column

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adds phone column directly after email
            $table->string('phone')->nullable()->after('email');

            // Adds country_code as the very first column in the table
            // $table->string('country_code', 2)->first();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
```

## Adding Multiple Ordered Columns

```php
Schema::table('orders', function (Blueprint $table) {
    $table->after('total_amount', function (Blueprint $table) {
        $table->decimal('discount_amount', 10, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
    });
});
```

## Summary

- Supported on MySQL and MariaDB databases.
- Keeps table column layouts logical and organized for database GUI tools and team debugging.
- Use `->first()` to place a column at the beginning of the table.
