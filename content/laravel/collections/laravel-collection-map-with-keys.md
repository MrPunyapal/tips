---
category: "Laravel"
tags: ["Laravel", "Collections", "Data Transformation"]
date: "2023-10-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Transform Collections into Key-Value Maps with mapWithKeys()

> Use mapWithKeys() to transform collections into associative dictionaries with custom keys and values in a single operation.

When converting an Eloquent collection or dataset into a keyed associative array for dropdowns, chart data, or JSON dictionaries, combining `keyBy()` and `map()` loops over the collection multiple times.

`mapWithKeys()` allows you to define both the new key and new value simultaneously.

## Basic Key-Value Mapping

```php
use App\Models\User;

$users = User::all();

$lookup = $users->mapWithKeys(function (User $user) {
    return [$user->email => $user->name];
});

// Output:
// [
//     'punyapal@example.com' => 'Punyapal Shah',
//     'alex@example.com'     => 'Alex Mercer',
// ]
```

## Formatting Option Lists for Form Selects

```php
$options = User::all()->mapWithKeys(function (User $user) {
    $label = "{$user->name} ({$user->department})";
    return [$user->id => $label];
});
```

## Multiple Key-Value Pairs from Single Items

A single item can return multiple key-value pairs if needed:

```php
$rates = collect($plans)->mapWithKeys(function ($plan) {
    return [
        $plan['id'] . '_monthly' => $plan['monthly_price'],
        $plan['id'] . '_annual'  => $plan['annual_price'],
    ];
});
```

## Summary

- Transforms items into key-value pairs in a single collection pass.
- Closure must return an associative array with `[key => value]`.
- Ideal for building select dropdown options and API lookup dictionaries.
