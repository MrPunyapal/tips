---
category: "Laravel"
tags: ["Laravel", "Collections", "Data Transformation", "Algorithms"]
date: "2023-10-04"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Create Rolling Windows and Pairings with Collection::sliding()

> Use sliding() on Laravel Collections to generate overlapping chunks for rolling averages, moving trends, and consecutive item comparisons.

When comparing consecutive items in a collection (such as calculating day-over-day changes or computing 3-day rolling averages), manual looping with index tracking (`$items[$i] - $items[$i - 1]`) requires boundary checks.

`sliding()` returns a new collection of overlapping chunks (a "sliding window") over your data.

## Basic Sliding Window

```php
$data = collect([1, 2, 3, 4, 5]);

// Sliding window of size 2
$pairs = $data->sliding(2);

// Result:
// [[1, 2], [2, 3], [3, 4], [4, 5]]
```

## Calculating Day-Over-Day Changes

```php
$dailyRevenues = collect([100, 120, 115, 140, 160]);

// Compare each day with the previous day
$changes = $dailyRevenues->sliding(2)->map(function ($window) {
    [$previous, $current] = $window->values();
    return $current - $previous;
});

// Result: [20, -5, 25, 20]
```

## Custom Step Intervals

You can also specify a custom step interval as the second argument:

```php
// Sliding window of size 3, stepping by 2 items each time
$chunks = collect([1, 2, 3, 4, 5, 6])->sliding(3, 2);
// Result: [[1, 2, 3], [3, 4, 5]]
```

## Summary

- Generates overlapping collections of size (N).
- Eliminates manual array index arithmetic and out-of-bounds errors.
- Ideal for timeline analysis, moving averages, and consecutive diff calculations.
