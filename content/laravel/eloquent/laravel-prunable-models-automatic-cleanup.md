---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Maintenance", "Architecture"]
date: "2023-01-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Automate Database Cleanup with Prunable and MassPrunable

> Use Laravel's Prunable and MassPrunable traits to periodically delete stale database records using scheduled Artisan commands.

Applications frequently accumulate transient data: expired authentication tokens, unverified accounts, old activity logs, and discarded shopping carts.

Laravel provides the `Prunable` and `MassPrunable` traits to declare pruning queries directly on the model.

## Implementing Prunable

Add the `Prunable` trait and define a `prunable()` method returning a query builder instance:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ActivityLog extends Model
{
    use Prunable;

    public function prunable(): Builder
    {
        // Delete records older than 90 days
        return static::where('created_at', '<=', now()->subDays(90));
    }

    protected function pruning(): void
    {
        // Optional hook: Clean up related storage files before deletion
    }
}
```

## High-Performance Cleanup with MassPrunable

If you do not need model deletion events or file cleanup hooks, use `MassPrunable`. It executes a single `DELETE` query directly in SQL for maximum throughput:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class TempUpload extends Model
{
    use MassPrunable;

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subHours(24));
    }
}
```

## Scheduling Pruning

Register the `model:prune` command in your application scheduler:

```php
use IlluminateSupportFacadesSchedule;

Schedule::command('model:prune')->daily();
```

## Summary

- `Prunable` fires Eloquent model events and invokes the `pruning()` method for each deleted record.
- `MassPrunable` runs direct SQL delete queries in chunks without firing individual model events.
- Run `php artisan model:prune` or schedule it daily to keep table sizes optimal.
