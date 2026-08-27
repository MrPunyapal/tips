---
category: "Laravel"
tags: ["Laravel", "Collections", "Arrays", "Data Transformation"]
date: "2024-09-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Flatten and Restore Nested Arrays with Collection::dot() and undot()

> Use dot() to flatten nested multi-dimensional arrays into single-level dot notation maps, and undot() to reconstruct full nested structures.

When storing nested configuration dictionaries in database key-value stores or converting JSON payloads for form inputs, manipulating nested arrays is complex.

Laravel Collections provide `dot()` and `undot()`.

## Flattening with dot()

```php
$settings = collect([
    'app' => [
        'theme' => 'dark',
        'notifications' => [
            'email' => true,
            'sms'   => false,
        ]
    ]
]);

$flattened = $settings->dot();

// Output:
// [
//     'app.theme' => 'dark',
//     'app.notifications.email' => true,
//     'app.notifications.sms' => false,
// ]
```

## Reconstructing Nested Structures with undot()

```php
$dotArray = collect([
    'user.name' => 'Punyapal',
    'user.contact.email' => 'punyapal@example.com',
]);

$nested = $dotArray->undot();

// Output:
// [
//     'user' => [
//         'name' => 'Punyapal',
//         'contact' => [
//             'email' => 'punyapal@example.com'
//         ]
//     ]
// ]
```

## Summary

- `dot()` flattens multi-level hierarchies into dot-notated single-level arrays.
- `undot()` expands dot-notated keys back into multidimensional structures.
- Essential for config caches and dynamic settings forms.
