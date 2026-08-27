---
category: "Laravel"
tags: ["Laravel", "HTTP", "Carbon", "Dates"]
date: "2022-11-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Retrieve Input Dates as Carbon Instances with $request->date()

> Use $request->date() to parse incoming HTTP request date strings directly into Carbon instances without manual Carbon::parse() boilerplate.

When validating and querying date filters (such as analytics date ranges or booking schedules), converting request strings into Carbon instances manually with `Carbon::parse($request->input('start_date'))` is repetitive and requires manual null checking.

Laravel provides the `$request->date()` method on all request objects.

## Basic Date Retrieval

```php
use Illuminate\Http\Request;

public function index(Request $request)
{
    // Returns a Carbon instance or null if the field is not present
    $startDate = $request->date('start_date');
    $endDate = $request->date('end_date') ?? now();

    $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
}
```

## Custom Date Formats and Timezones

Pass format strings and target timezones directly as optional arguments:

```php
// Parse specific format and convert timezone
$scheduledAt = $request->date('scheduled_at', 'd/m/Y H:i', 'America/New_York');
```

## Summary

- Returns an `Illuminate\Support\Carbon` instance or `null`.
- Prevents fatal errors from calling Carbon methods on empty request parameters.
- Accepts format specifications and custom timezone offsets.
