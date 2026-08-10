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

Accidentally running migrate:fresh on production is every team's nightmare. Laravel provides DB::prohibitDestructiveCommands() to disallow destructive commands in production.

```php
public function boot(): void
{
    DB::prohibitDestructiveCommands($this->app->isProduction());
}
```

- Guards migrate:fresh, migrate:refresh, migrate:reset, and db:wipe
- Configured once in AppServiceProvider::boot()
- Throws command failure exit code if executed in production
