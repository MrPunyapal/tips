---
category: "Laravel"
tags: ["Laravel", "Migrations", "Database"]
date: "2026-08-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Simplify ULID Foreign Keys with foreignUlidFor()

> Laravel 13.25 adds foreignUlidFor() to the migration Blueprint, allowing you to define ULID foreign key columns and table constraints directly from model classes.

When building applications where models use ULIDs for primary keys, setting up foreign key columns in database migrations previously required declaring both the ULID column and its foreign constraint manually.

Laravel 13.25 introduces the `foreignUlidFor()` schema helper on the `Illuminate\Database\Schema\Blueprint` class. This method automatically creates the ULID column and establishes the foreign key constraint based on the given model.

## Before and After

### Before Laravel 13.25

Defining a ULID foreign key required manually declaring the ULID column and chaining foreign key constraints:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('posts', function (Blueprint $table) {
    $table->id();

    // Manual ULID column and foreign key constraint
    $table->ulid('user_id');
    $table->foreign('user_id')
        ->references('id')
        ->on('users');

    $table->timestamps();
});
```

### With foreignUlidFor() in Laravel 13.25

Using `foreignUlidFor()`, the column name and table constraint are resolved directly from the model class:

```php
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('posts', function (Blueprint $table) {
    $table->id();

    // Creates user_id ULID column and foreign key constraint to users table
    $table->foreignUlidFor(User::class);

    $table->timestamps();
});
```

## How It Works

Passing a model class (such as `User::class`) to `foreignUlidFor()` performs two steps during migration execution:

1. Creates a ULID column matching the conventional model foreign key name (`user_id`).
2. Configures the foreign key constraint referencing the `id` column on the associated model table (`users`).

The helper assumes that the referenced model uses ULIDs for its primary keys.

## Key Considerations

- **Migration Schema Helper Only**: `foreignUlidFor()` is a database schema helper for migration files. It does not define Eloquent model relationships or inverse relationship methods on Eloquent model classes.
- **New Migrations Scope**: This helper applies when defining new tables or altering schemas inside active migration files. It does not automatically modify existing database tables or retroactively update previously executed migrations.
- **Convention Driven**: The column name and table target are derived from standard model conventions (`User` resolves to `user_id` and table `users`).
