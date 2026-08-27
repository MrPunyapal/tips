---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Carbon", "Dates"]
date: "2024-10-09"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Standardize Database Timestamps and Format Dates with Eloquent Date Casts

> Store all dates in UTC database timestamps and use Eloquent date casting and Carbon accessors for display formatting.

Parsing date strings repeatedly inside Blade views with manual `Carbon::parse($order->ordered_at)->format('M d, Y')` calls is slow, repetitive, and fails if the column value is null.

Eloquent date casting automatically converts database timestamp strings into immutable Carbon instances upon retrieval.

## Defining Date Casts on Models

```php
namespace AppModels;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
            'trial_ends_at' => 'immutable_datetime',
        ];
    }
}
```

## Accessing Fluent Carbon Methods

Once cast, properties automatically return Carbon instances with full date math and comparison capabilities:

```php
$subscription = Subscription::find(1);

// Date comparison
if ($subscription->ends_at->isPast()) {
    $subscription->markAsExpired();
}

// Relative human diffs
echo $subscription->ends_at->diffForHumans(); // "in 3 days"
```

## In Blade Templates

```blade
<p class="text-sm">
    Subscription active until {{ $subscription->ends_at->format('M d, Y') }}
</p>
```

## Summary

- Store timestamps in standard SQL `TIMESTAMP` / `DATETIME` format in UTC.
- `datetime` and `immutable_datetime` casts ensure attributes hydrate as Carbon instances.
- Simplifies timezone conversions, relative formatting, and chronological comparisons.
