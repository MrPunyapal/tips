---
category: "Laravel"
tags: ["Laravel", "Queue", "Rate Limiting", "Architecture"]
date: "2023-12-06"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Throttle Background Queue Jobs with RateLimiter::for()

> Use RateLimiter inside queue job middleware to throttle background job execution against third-party API rate limits.

When background queue workers process external API calls (such as sending transactional emails via SendGrid or synchronizing data with Shopify), workers can easily exceed third-party API rate limits.

Laravel provides rate limiting middleware directly for queued jobs.

## Defining Rate Limiters for Jobs

In `AppServiceProvider::boot()`:

```php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Limit third-party API sync to 10 jobs per minute
        RateLimiter::for('shopify-api', function ($job) {
            return Limit::perMinute(10)->by($job->account_id);
        });
    }
}
```

## Applying Middleware in Queue Jobs

```php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;

class SyncShopifyOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public int $account_id) {}

    public function middleware(): array
    {
        // Automatically releases job back to queue if rate limit is reached
        return [new RateLimited('shopify-api')];
    }

    public function handle(): void
    {
        // Make rate-limited API calls safely
    }
}
```

## Summary

- Releases jobs back onto the queue automatically when rate limits are reached.
- Prevents 429 API threshold blocks from third-party services.
- Partitions limits dynamically by tenant ID or account ID.
