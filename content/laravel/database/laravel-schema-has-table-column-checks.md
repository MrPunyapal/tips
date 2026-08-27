---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "Schema"]
date: "2024-02-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Conditionally Check Database Tables and Columns with Schema Facade

> Use Schema::hasTable() and Schema::hasColumn() to write safe, defensive migrations and dynamic installer scripts.

When writing data migrations across environments, package installers, or multi-tenant database upgrades, attempting to alter a table or column that does not exist throws database exceptions.

The `Schema` facade provides helper methods to inspect database structure before running modifications.

## Checking Table and Column Existence in Migrations

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify table only if it exists
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                // Add column only if it hasn't been added yet
                if (! Schema::hasColumn('orders', 'tracking_number')) {
                    $table->string('tracking_number')->nullable()->after('status');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'tracking_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('tracking_number');
            });
        }
    }
};
```

## Checking Multiple Columns Simultaneously

Pass an array to `Schema::hasColumns()` to verify multiple columns exist:

```php
if (Schema::hasColumns('users', ['email', 'phone', 'billing_address'])) {
    // All specified columns exist in the table
}
```

## Summary

- Prevents `Table already exists` or `Column not found` errors during dynamic schema operations.
- `Schema::hasTable('table')` inspects table existence.
- `Schema::hasColumn('table', 'column')` and `Schema::hasColumns('table', [...])` inspect column definitions.
