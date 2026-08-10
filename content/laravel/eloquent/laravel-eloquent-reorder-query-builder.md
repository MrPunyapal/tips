---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2024-03-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Clear Query Order Constraints with reorder()

> Use reorder() to clear previous orderBy clauses from a query builder before applying new sorting constraints.

When modifying existing query builders or model scopes that already contain orderBy clauses, appending another orderBy appends a secondary sort. reorder() strips existing ordering rules.

```php
use App\Models\User;

$query = User::orderBy('name', 'asc');

// Replaces 'name' sorting with 'created_at' sorting
$users = $query->reorder('created_at', 'desc')->get();
```

- Strips all existing orderBy clauses from the query builder
- Accepts optional new column and direction arguments to re-apply sorting
- Essential when overriding default sorting rules defined in model scopes
