---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Query Builder"]
date: "2024-02-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Build Conditional Queries Cleanly with when() and unless()

> Use when() and unless() on query builders to apply conditional clauses without breaking method chains with if statements.

Building dynamic search queries often breaks method chains with if ($search) { $query->where(...) }. The when() and unless() methods evaluate conditions inline within query chains.

```php
use App\Models\User;

$users = User::query()
    ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
    ->unless($request->include_archived, fn ($q) => $q->whereNull('archived_at'))
    ->get();
```

- Keeps query builder method chains fluent without breaking into if statements
- when() executes closure if first parameter evaluates to truthy
- unless() executes closure if first parameter evaluates to falsey
