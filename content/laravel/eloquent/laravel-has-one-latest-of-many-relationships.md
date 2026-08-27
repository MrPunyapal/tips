---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Relationships", "Performance"]
date: "2022-10-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Define One-to-One Subsets with latestOfMany() and oldestOfMany()

> Create direct single-model relationships for the newest or oldest item in a one-to-many relationship using ofMany() helpers.

When an entity owns multiple child records (such as a Customer having many Orders, or a User having many Logins), accessing only the most recent child often leads to loading all children and sorting in memory.

Laravel provides `latestOfMany()` and `oldestOfMany()` to define efficient `HasOne` relationships backed by a SQL subquery.

## Defining the Relationship

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function latestOrder(): HasOne
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }

    public function oldestOrder(): HasOne
    {
        return $this->hasOne(Order::class)->oldestOfMany();
    }
}
```

## Custom Aggregate Sorting

You can also specify custom column sorting (such as the highest order amount):

```php
public function largestOrder(): HasOne
{
    return $this->hasOne(Order::class)->ofMany('total_amount', 'max');
}
```

## Eager Loading and Query Usage

Because `latestOrder` is a standard `HasOne` relationship, it can be eager-loaded without fetching unnecessary historical records:

```php
$customers = Customer::with('latestOrder')->get();

foreach ($customers as $customer) {
    echo $customer->latestOrder?->total_amount;
}
```

## Summary

- Eliminates N+1 memory overhead by querying single record subsets in SQL.
- Works as a standard `HasOne` relationship that supports full eager loading.
- Supports custom aggregates via `ofMany('column', 'max')` or `ofMany('column', 'min')`.
