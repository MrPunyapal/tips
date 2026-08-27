---
category: "Laravel"
tags: ["Laravel", "Database", "Performance", "Monitoring"]
date: "2025-03-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Detect Cumulative Database Query Spikes with whenQueryingForLongerThan()

> Use DB::whenQueryingForLongerThan() to monitor the cumulative time spent in database queries per HTTP request and notify developers of slow requests.

While `DB::listen()` measures individual query duration, an endpoint that executes 100 fast 5ms queries spends a massive 500ms in SQL without triggering single-query slow thresholds.

`DB::whenQueryingForLongerThan()` measures the cumulative total database time across an entire request.

## Setting Cumulative Query Thresholds

In `AppServiceProvider::boot()`:

```php
namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Trigger alert if a single request spends more than 500ms in total database queries
        DB::whenQueryingForLongerThan(CarbonInterval::milliseconds(500), function ($connection) {
            Log::warning("Database query threshold exceeded [{$connection->totalQueryDuration()}ms]", [
                'url' => request()->fullUrl(),
                'user_id' => auth()->id(),
            ]);
        });
    }
}
```

## Summary

- Measures total aggregate SQL duration per HTTP request lifecycle.
- Catches cumulative N+1 query regressions that individual query thresholds miss.
- Accepts `CarbonInterval` instances for clean threshold definitions.
