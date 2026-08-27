---
category: "Laravel"
tags: ["Laravel", "Collections", "Data Transformation"]
date: "2024-11-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Combine Multiple Arrays by Index with Collection::zip()

> Use zip() on Laravel Collections to merge array elements sharing matching index positions into paired sub-collections.

When processing paired tabular data (such as headers and corresponding CSV row values, or parallel arrays of names and scores), indexing arrays manually with `$names[$i]` requires bounds checking.

`zip()` pairs corresponding items from multiple arrays together.

## Basic Usage

```php
$names = collect(['Punyapal', 'Developer 2', 'Developer 3']);
$scores = [95, 88, 92];

$zipped = $names->zip($scores);

// Output:
// [
//     ['Punyapal', 95],
//     ['Developer 2', 88],
//     ['Developer 3', 92],
// ]
```

## Zipping Multiple Datasets

```php
$users = collect(['User 1', 'User 2']);
$roles = ['Admin', 'Editor'];
$status = ['Active', 'Pending'];

$matrix = $users->zip($roles, $status);
// Output: [['User 1', 'Admin', 'Active'], ['User 2', 'Editor', 'Pending']]
```

## Summary

- Merges elements of matching index positions across multiple arrays.
- Fills missing values with `null` if array lengths differ.
- Ideal for data matrix transformations and CSV row construction.
