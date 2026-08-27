---
category: "Laravel"
tags: ["Laravel", "Formatting", "Utilities", "Localization"]
date: "2023-03-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Format Currencies, Percentages, and Ordinals with the Number Utility

> Use Laravel's Number utility class to format numbers into localized currencies, percentages, and spelled-out words.

Formatting numbers for localized user interfaces traditionally required configuring PHP's `NumberFormatter` extension manually.

Laravel provides the `Illuminate\Support\Number` utility class.

## Formatting Currencies

```php
use Illuminate\Support\Number;

echo Number::currency(1500, in: 'USD');
// "$1,500.00"

echo Number::currency(2490.50, in: 'EUR', locale: 'de');
// "2.490,50 €"

echo Number::currency(50000, in: 'INR');
// "₹50,000.00"
```

## Formatting Percentages and Compact Numbers

```php
// Percentages
echo Number::percentage(0.854, precision: 1);
// "85.4%"

// Compact abbreviated numbers
echo Number::abbreviate(1250000);
// "1.3M"

echo Number::abbreviate(4500);
// "4.5K"
```

## Spelling Out Numbers and Ordinals

```php
echo Number::spell(42);
// "forty-two"

echo Number::ordinal(3);
// "3rd"
```

## Summary

- Formats localized currency amounts with ISO 4217 codes.
- Abbreviates large numbers into human-readable notation (e.g. `1.2M`, `500K`).
- Spells out integer counts and ordinal positions for natural UI copy.
