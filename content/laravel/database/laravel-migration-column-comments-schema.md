---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "Documentation"]
date: "2023-11-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Document Database Schemas Directly in Migrations with comment()

> Add inline database comments to table columns using ->comment('...') to maintain clear schema documentation for your team.

When database tables store status integers, bitmasks, or specific currency units (such as storing amounts in cents), developers often have to check model code or documentation to understand column semantics.

Laravel's schema builder provides the `->comment()` modifier to write descriptions directly into database table definitions.

## Adding Column Comments

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
            
            // Document integer amount representations
            $table->unsignedInteger('amount_cents')
                ->comment('Total order amount stored in smallest currency unit (cents)');

            // Document status codes
            $table->tinyInteger('status')
                ->default(0)
                ->comment('0: Draft, 1: Pending, 2: Paid, 3: Refunded');

            $table->timestamps();
        });
    }
};
```

## Viewing in Database Tools

Database management tools (TablePlus, DataGrip, phpMyAdmin, DBeaver) display column comments in schema views and query auto-completion tooltips, giving developers immediate context without opening codebases.

## Summary

- Stores documentation directly inside MySQL, PostgreSQL, and SQLite database metadata.
- Explains unit formats (cents, milliseconds) and enum/status integer maps.
- Keeps database schemas self-documenting for database administrators and backend engineers.
