---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "Macros"]
date: "2026-08-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Reusable Migration Fields with Custom Blueprint Macros

> Extend Laravel's Blueprint class with custom macros to standardize and reuse common schema columns across database migrations.

Database migrations often require repeating the same group of audit columns across multiple tables. Repeating these field definitions across dozens of migrations creates maintenance overhead and inconsistencies.

## The Problem: Repetitive Schema Definitions

When multiple tables require tracking creation, updates, soft deletions, and restoration details, a standard migration file quickly becomes verbose:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');

    // Repeated audit columns
    $table->timestamps();
    $table->softDeletes();
    $table->timestamp('restored_at')->nullable();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->unsignedBigInteger('updated_by')->nullable();
    $table->unsignedBigInteger('deleted_by')->nullable();
    $table->unsignedBigInteger('restored_by')->nullable();
});
```

Duplicating these 8 lines across every new table definition increases boilerplate code and makes project-wide schema changes tedious.

## Registering a Custom Blueprint Macro

Laravel's `Illuminate\Database\Schema\Blueprint` class can be extended using macros. Registering a custom macro allows you to bundle related column definitions into a single helper method inside `AppServiceProvider`:

```php
namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blueprint::macro('auditFields', function () {
            $this->timestamps();
            $this->softDeletes();

            $this->timestamp('restored_at')->nullable();

            $this->unsignedBigInteger('created_by')->nullable();
            $this->unsignedBigInteger('updated_by')->nullable();
            $this->unsignedBigInteger('deleted_by')->nullable();
            $this->unsignedBigInteger('restored_by')->nullable();
        });
    }
}
```

### What These Fields Represent

- `timestamps()`: Adds standard `created_at` and `updated_at` timestamps.
- `softDeletes()`: Adds a nullable `deleted_at` timestamp for soft deletion.
- `restored_at`: Tracks the precise timestamp when a soft-deleted record is restored.
- `created_by`, `updated_by`, `deleted_by`, `restored_by`: Store the user IDs of the accounts responsible for each state change.

## Reusing the Macro Across Migrations

Once registered, the `auditFields()` macro is available on `$table` instances in any migration file.

### Users Migration

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();

            $table->auditFields();
        });
    }
};
```

### Posts Migration

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');

            $table->auditFields();
        });
    }
};
```

Both tables receive identical audit field structures while keeping migration files concise.

## Customizing Project Conventions

Macros can be tailored to fit your application's specific conventions. If a project requires additional metadata columns, tenant identifiers, or custom status attributes across multiple tables, they can be defined within the macro body.

## Why This Is Useful

Defining schema macros centralizes multi-column definitions into a single location in your codebase. If your application conventions expand to include additional columns, updating the macro definition ensures future migrations include the new standard structure.

Note that registering or modifying a macro does not alter existing database tables or retroactively update past migrations. Macros expand to standard column definitions only when executed during active migration runs.
