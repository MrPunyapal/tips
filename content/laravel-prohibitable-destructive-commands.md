---
category: "Laravel"
tags: ["Laravel", "Database", "Security", "Artisan"]
date: "2026-05-02"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Protect Production with Laravel Prohibitable Commands

> Prevent catastrophic accidents like `db:wipe` or `migrate:fresh` in production using Laravel's `Prohibitable` trait and `DB::prohibitDestructiveCommands()`.

Accidentally running `php artisan migrate:fresh` or `db:wipe` on a production database is every team's worst nightmare.

Laravel provides `DB::prohibitDestructiveCommands()` and the `Prohibitable` trait to completely disallow destructive commands in production.

### Guard standard destructive commands in `AppServiceProvider`:

```php
namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Prohibits migrate:fresh, migrate:refresh, migrate:reset, and db:wipe in production
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }
}
```

### Make custom Artisan commands prohibitable:

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;

class PurgeUserDataCommand extends Command
{
    use Prohibitable;

    protected $signature = 'users:purge-inactive';
    protected $description = 'Purge all unverified users older than 90 days';

    public function handle(): int
    {
        if ($this->isProhibited()) {
            return Command::FAILURE;
        }

        // Safe purge logic...
        return Command::SUCCESS;
    }
}
```
