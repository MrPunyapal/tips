---
category: "Laravel"
tags: ["Laravel", "Scheduler", "DevOps", "Artisan"]
date: "2023-04-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Restrict Scheduled Tasks by Environment with environments()

> Use environments() on scheduled Artisan commands to restrict task execution strictly to specific environments (like production or staging).

Certain scheduled tasks (such as customer billing charges, production backup uploads, or external status pings) should only ever run in production, while sandbox synchronization jobs may only belong on staging or local environments.

Laravel's task scheduler provides the `environments()` constraint.

## Restricting Tasks to Specific Environments

In `routes/console.php` or `app/Console/Kernel.php`:

```php
use Illuminate\Support\Facades\Schedule;

// Runs only on production
Schedule::command('billing:charge-monthly-invoices')
    ->monthlyOn(1, '00:00')
    ->environments(['production']);

// Runs on staging and local only
Schedule::command('sandbox:refresh-test-data')
    ->daily()
    ->environments(['staging', 'local']);
```

## Dynamic Conditional Scheduling with when()

For custom runtime checks beyond environment names:

```php
Schedule::command('reports:generate')
    ->daily()
    ->when(function () {
        // Runs only if reporting feature flag is enabled
        return config('features.reporting_enabled') === true;
    });
```

## Summary

- Prevents accidental execution of production jobs on local or staging servers.
- Accepts an array of environment strings matching `APP_ENV`.
- Cleanly replaces manual `if (app()->isProduction())` checks inside scheduled closures.
