---
category: "Laravel"
tags: ["Laravel", "Database", "Migrations", "Clean Code"]
date: "2023-10-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Eliminate Migration Class Name Collisions with Anonymous Migrations

> Use anonymous class migrations (return new class extends Migration) to eliminate class name collisions in large Laravel applications.

In traditional Laravel migrations, class names (such as `class CreateUsersTable`) had to match the filename. When modifying tables across multiple versions or running multi-package apps, class name collisions frequently broke migration suites.

Laravel uses anonymous migration classes by default.

## Anonymous Migration Structure

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_id')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
```

## Generating Anonymous Migrations

Standard Artisan commands generate anonymous migrations automatically:

```bash
php artisan make:migration create_subscriptions_table
```

## Summary

- Prevents class redeclaration errors when adding multiple migrations for the same table.
- Removes the need to maintain matching class and file names.
- Default standard across all modern Laravel versions.
