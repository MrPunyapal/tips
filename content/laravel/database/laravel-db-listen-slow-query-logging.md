---
category: "Laravel"
tags: ["Laravel", "Database", "Performance", "Debugging"]
date: "2023-02-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Log and Audit Slow Database Queries with DB::listen()

> Use DB::listen() in AppServiceProvider to monitor executed queries, log slow operations, and inspect raw SQL bindings during local development.

Debugging database performance bottlenecks requires visibility into every executed query, its execution time, and bound parameters.

Laravel provides `DB::listen()` to register a callback executed after every database query.

## Logging Slow Queries in Development

Add the listener inside `AppServiceProvider::boot()`:

```php
namespace AppProviders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use IlluminateSupportServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        DB::listen(function ($query) {
            // Log any query that takes longer than 100ms
            if ($query->time > 100) {
                Log::warning("Slow Query [{$query->time}ms]: {$query->sql}", [
                    'bindings'   => $query->bindings,
                    'connection' => $query->connectionName,
                ]);
            }
        });
    }
}
```

## Inspecting Query Properties

- **`$query->sql`**: The prepared SQL statement (e.g. `select * from users where email = ?`).
- **`$query->bindings`**: Array of bound parameters.
- **`$query->time`**: Execution time in milliseconds.
- **`$query->connectionName`**: Database connection identifier (e.g. `mysql`, `pgsql`).

## Summary

- Intercepts all database queries application-wide.
- Enables threshold-based slow query logging.
- Essential tool for identifying missing indexes and unoptimized joins during development.
