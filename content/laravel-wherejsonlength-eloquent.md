---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2024-01-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Filter Records by JSON Array Size with whereJsonLength()

> Use whereJsonLength() to query database records based on the number of elements in a JSON array attribute.

Filtering records by the number of elements stored in a JSON array column is straightforward in Eloquent using whereJsonLength().

```php
use App\Models\User;

// Select users with more than 2 items in their JSON options->tags array
$users = User::whereJsonLength('options->tags', '>', 2)->get();
```

- Queries database JSON array length using native database JSON functions
- Supports comparison operators (=, >, <, >=, <=)
- Works with MySQL, PostgreSQL, and SQLite database drivers
