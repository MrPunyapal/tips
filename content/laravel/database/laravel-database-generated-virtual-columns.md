---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "Performance"]
date: "2025-08-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Index Dynamic JSON Attributes with Generated Virtual Columns

> Use virtualAs() and storedAs() in migrations to create generated database columns for high-speed indexing on JSON attributes.

Querying deep JSON attributes (such as `WHERE JSON_EXTRACT(metadata, '$.country') = 'US'`) cannot use standard B-Tree column indexes, resulting in slow full table scans on large tables.

Generated columns extract values from JSON or string expressions and allow standard indexing.

## Creating Generated Columns in Migrations

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('payload');

            // Virtual column computed on read (MySQL / PostgreSQL / SQLite)
            $table->string('currency')->virtualAs("payload->>'$.currency'");

            // Stored column saved physically on disk for indexing
            $table->decimal('total_amount', 10, 2)->storedAs("payload->>'$.total'");

            // Add standard fast B-Tree index on the generated column!
            $table->index('total_amount');

            $table->timestamps();
        });
    }
};
```

## Querying Naturally in Eloquent

```php
// Fast indexed query on the generated column!
$largeOrders = Order::where('total_amount', '>', 500)->get();
```

## Summary

- `virtualAs()`: Computes value dynamically during reads without disk storage.
- `storedAs()`: Computes value on insert/update and stores on disk, allowing standard B-Tree index creation.
- Accelerates heavy queries on JSON payloads by 10x–100x.
