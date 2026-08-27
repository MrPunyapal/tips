---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance"]
date: "2023-12-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Sort Eloquent Results by Subquery Columns with orderByDesc()

> Use query builder subqueries inside orderBy() and orderByDesc() to sort models by aggregated or related table columns directly in SQL.

Sorting records by related data (such as sorting users by the date of their latest login, or ordering categories by their total sales) often leads developers to sort Eloquent collections in PHP memory using `sortBy()`.

Sorting in memory requires loading all records into RAM and breaks database pagination. You can pass a subquery directly to `orderBy()`.

## Sorting by a Related Subquery

```php
use App\Models\Order;
use App\Models\User;

// Sort users by the timestamp of their most recent order
$users = User::orderByDesc(
    Order::select('created_at')
        ->whereColumn('orders.user_id', 'users.id')
        ->latest()
        ->limit(1)
)->paginate(20);
```

## Sorting by Calculated Aggregate Subquery

```php
use App\Models\Book;
use App\Models\Review;

// Sort books by their average rating
$books = Book::orderByDesc(
    Review::selectRaw('AVG(rating)')
        ->whereColumn('reviews.book_id', 'books.id')
)->paginate(15);
```

## Summary

- Executes sorting directly inside the database engine before pagination limits apply.
- Works directly with `paginate()` and preserves minimal memory footprints.
- Eliminates the need to load large collections into PHP memory just for sorting.
