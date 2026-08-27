---
category: "Laravel"
tags: ["Laravel", "Collections", "Validation", "Data Transformation"]
date: "2023-10-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Identify and Extract Duplicate Values with Collection::duplicates()

> Use duplicates() on Laravel Collections to quickly find and extract duplicate values or duplicate model attributes from a dataset.

When validating imported CSV spreadsheets, checking for duplicate emails in an array, or auditing datasets, filtering duplicates manually requires nested loops or array count checks.

The `duplicates()` method finds all duplicate items and returns their original array keys.

## Basic Duplicate Detection

```php
$emails = collect([
    'alex@example.com',
    'punyapal@example.com',
    'sarah@example.com',
    'alex@example.com', // Duplicate
]);

$duplicates = $emails->duplicates();

// Output: [3 => 'alex@example.com']
```

## Checking Duplicate Object Attributes

Pass a column name or key to check for duplicates inside object or associative collections:

```php
$employees = collect([
    ['id' => 1, 'email' => 'alex@example.com'],
    ['id' => 2, 'email' => 'punyapal@example.com'],
    ['id' => 3, 'email' => 'alex@example.com'],
]);

$duplicateEmails = $employees->duplicates('email');

// Output: [2 => 'alex@example.com']
```

## Summary

- Returns a new collection containing only the duplicated values with their original keys.
- Accepts key strings or closures to detect duplicate nested attributes.
- Perfect for CSV import validation and batch data cleansing.
