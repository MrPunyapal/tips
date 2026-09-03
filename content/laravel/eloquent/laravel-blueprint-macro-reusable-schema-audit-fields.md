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

Database migrations frequently repeat the same cluster of audit columns (such as created/updated timestamps, soft deletes, and user foreign keys) across multiple tables.

Using `Blueprint::macro()`, you can bundle these columns into a single reusable helper method.

---

## Register the Macro

Define your custom macro inside `AppServiceProvider::boot()`:

```php
namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blueprint::macro('auditFields', function (bool $softDeletes = true) {
            $this->timestamps();

            if ($softDeletes) {
                $this->softDeletes();
            }

            $this->foreignId('created_by')->nullable()->constrained('users');
            $this->foreignId('updated_by')->nullable()->constrained('users');
        });
    }
}
```

---

## Use Across Migrations

Call the macro directly on `$table` in any migration:

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

            // Injects timestamps, softDeletes, and audit user IDs
            $table->auditFields();
        });
    }
};
```

---

## Key Points

- **Consistency**: Guarantees identical column types, nullability, and foreign key constraints across all audited tables.
- **Execution Timing**: Macros expand into standard column definitions when `php artisan migrate` runs; they do not retroactively alter already-migrated tables.
- **Parametric Flexibility**: Add arguments to your macro closure (like `$softDeletes = true`) to adapt column inclusion per table.
