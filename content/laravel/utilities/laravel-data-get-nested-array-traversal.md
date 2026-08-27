---
category: "Laravel"
tags: ["Laravel", "Utilities", "Arrays", "Clean Code"]
date: "2023-06-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Safely Traverse Deeply Nested Arrays and Objects with data_get()

> Use the data_get() helper to read values from nested arrays, objects, and JSON payloads using dot notation without undefined index errors.

When reading data from third-party API payloads or nested database JSON columns (such as `$payload['order']['customer']['address']['city']`), missing keys at any level throw `Undefined array key` or null reference errors.

The `data_get()` helper traverses nested structures safely using dot notation.

## Basic Nested Traversal

```php
$payload = [
    'order' => [
        'customer' => [
            'name' => 'Punyapal Shah',
            'address' => [
                'city' => 'Surat',
            ]
        ]
    ]
];

// Returns 'Surat'
$city = data_get($payload, 'order.customer.address.city');

// Returns 'Default State' without throwing errors if key is missing
$state = data_get($payload, 'order.customer.address.state', 'Default State');
```

## Traversing Arrays with Asterisk Wildcards

`data_get()` can extract attributes from collections of items:

```php
$order = [
    'items' => [
        ['name' => 'Keyboard', 'price' => 100],
        ['name' => 'Mouse',    'price' => 50],
    ]
];

// Returns: ['Keyboard', 'Mouse']
$productNames = data_get($order, 'items.*.name');
```

## Working with Mixed Objects and Arrays

`data_get()` handles combinations of associative arrays, Eloquent models, and standard PHP objects directly.

## Summary

- Navigates nested arrays and objects using dot notation (`user.profile.avatar`).
- Returns fallback default values when intermediate keys are absent.
- Supports `*` wildcards to pluck values from sub-arrays in a single call.
