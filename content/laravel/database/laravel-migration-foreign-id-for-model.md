---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "Eloquent"]
date: "2025-04-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Define Type-Safe Foreign Keys with $table->foreignIdFor()

> Use $table->foreignIdFor(Model::class) in migrations to create foreign key columns that automatically match the model's primary key type and name.

Hardcoding foreign key column names like `$table->unsignedBigInteger('user_id')` can lead to type mismatches if the parent model changes primary key types (e.g. from integer to UUID).

`foreignIdFor()` infers column names and types directly from the target model class.

## Basic Usage in Migrations

```php
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Automatically generates 'user_id' matching User's primary key type
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            // Custom column name if needed
            $table->foreignIdFor(Company::class, 'client_company_id')->nullable()->constrained('companies');

            $table->timestamps();
        });
    }
};
```

## Summary

- Automatically derives column names (`user_id`) from model classes.
- Chains with `constrained()`, `nullOnDelete()`, and `cascadeOnDelete()`.
- Refactor-safe when changing model classes or database constraints.
