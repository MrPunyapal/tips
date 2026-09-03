---
category: "Laravel"
tags: ["Laravel", "Database", "Security"]
date: "2026-05-02"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Configuration"
---

# Protect Production with Laravel Prohibitable Commands

> Prevent catastrophic accidents like db:wipe or migrate:fresh in production using Laravel's Prohibitable trait and DB::prohibitDestructiveCommands().

Accidentally running `migrate:fresh` or `db:wipe` on a production database is catastrophic.

Laravel provides `DB::prohibitDestructiveCommands()` to block dangerous commands when running in production.

---

## AppServiceProvider Configuration

Add the call inside your `AppServiceProvider::boot()` method:

```php
namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Block destructive migration commands when in production
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );
    }
}
```

---

## Commands Protected

When prohibited, attempts to run the following commands fail with an exit error code:

- `php artisan db:wipe`
- `php artisan migrate:fresh`
- `php artisan migrate:refresh`
- `php artisan migrate:reset`

---

## Key Points

- **Zero Accidental Overrides**: Even passing the `--force` flag cannot bypass a prohibited command.
- **Environment Driven**: Passing `$this->app->isProduction()` keeps local, staging, and automated testing environments fully functional.
