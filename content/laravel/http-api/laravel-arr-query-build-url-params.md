---
category: "Laravel"
tags: ["Laravel", "Arr", "Helpers"]
date: "2023-06-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Convert Nested Arrays to URL Query Strings with Arr::query()

> Use Arr::query() to build properly encoded URL query strings directly from nested associative arrays.

Building complex query parameters manually using http_build_query() can be clunky. Laravel's Arr::query() helper converts arrays into clean URL-encoded query strings.

```php
use Illuminate\Support\Arr;

$array = ['filter' => ['status' => 'active', 'role' => 'admin'], 'page' => 2];

// Returns: 'filter%5Bstatus%5D=active&filter%5Brole%5D=admin&page=2'
$queryString = Arr::query($array);
```

- Converts nested associative arrays to URL-encoded query string format
- Handles nested key structures cleanly
- Ideal for constructing dynamic filter URLs for API or web links
