---
category: "Laravel"
tags: ["Laravel", "Carbon", "Dates", "Rector", "Refactoring"]
date: "2026-09-03"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Use Immutable Dates by Default in Laravel

> Configure Laravel's Date facade to use CarbonImmutable by default, and automate migrating legacy Carbon calls with Rector.

By default, Carbon instances in PHP are mutable. Modifying a date instance unexpectedly alters the original variable:

```php
$date = now();

$tomorrow = $date->addDay();

// Both variables now point to tomorrow!
echo $date->toDateString();     // 2026-09-04
echo $tomorrow->toDateString(); // 2026-09-04
```

---

## 1. Configure the Date Facade

In `AppServiceProvider::boot()`, instruct Laravel's `Date` factory to generate `CarbonImmutable` instances:

```php
namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Date::use(CarbonImmutable::class);
    }
}
```

Once configured, all calls through the `Date` facade as well as Laravel helpers (`now()`, `today()`) return immutable instances:

```php
$date = now();

$tomorrow = $date->addDay();

echo $date->toDateString();     // 2026-09-03 (original remains unchanged)
echo $tomorrow->toDateString(); // 2026-09-04
```

---

## 2. The Direct Carbon Trap

While `Date::use(CarbonImmutable::class)` secures `now()`, `today()`, and `Date::now()`, any code directly calling `Carbon::now()` or `Carbon::parse()` bypasses Laravel's date factory and remains mutable:

```php
use Carbon\Carbon;

// ❌ Bypasses Laravel's Date factory; remains mutable
$date = Carbon::parse('2026-09-03');

// ✅ Routes through Date factory; returns CarbonImmutable
$date = Date::parse('2026-09-03');
```

---

## 3. Automate Codebase Migration with Rector

To migrate an existing codebase so all static Carbon calls route through Laravel's `Date` facade, use [`CarbonToDateFacadeRector`](https://getrector.com/rule-detail/carbon-to-date-facade-rector) from `rector-laravel`:

```bash
composer require --dev driftingly/rector-laravel
```

Register the rule in `rector.php`:

```php
// rector.php
use Rector\Config\RectorConfig;
use RectorLaravel\Rector\StaticCall\CarbonToDateFacadeRector;

return RectorConfig::configure()
    ->withRules([
        CarbonToDateFacadeRector::class,
    ]);
```

### What Rector Automatically Transforms

```php
// Before Rector:
use Carbon\Carbon;

$now = Carbon::now();
$date = Carbon::parse('2026-01-01');

// After Rector:
use Illuminate\Support\Facades\Date;

$now = Date::now();
$date = Date::parse('2026-01-01');
```

---

## Key Benefits

- **Predictable Date Math**: Operations like `addDay()` or `subMonth()` always return new instances without mutating source dates.
- **Framework Uniformity**: Using `Date::use(CarbonImmutable::class)` guarantees all Eloquent timestamps, cast dates, and helpers share the same immutable behavior.
- **Automated Modernization**: `CarbonToDateFacadeRector` removes manual search-and-replace across legacy controllers and services.
