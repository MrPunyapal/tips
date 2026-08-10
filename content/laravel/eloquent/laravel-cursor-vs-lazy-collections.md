---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance"]
date: "2026-07-03"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Stream Large Datasets: cursor() vs lazy() in Eloquent

> cursor() hydrates single model instances sequentially using database cursors; lazy() streams records in chunks backed by LazyCollection.

When iterating millions of records, get() exhausts PHP memory limits. cursor() uses PDO cursors to fetch records one by one, while lazy() queries records in chunks while exposing a fluent LazyCollection.

```php
use App\Models\User;

// Cursors: single query, streams 1 instance at a time (lowest memory)
foreach (User::where('active', false)->cursor() as $user) {
    $user->archive();
}

// Lazy: queries in chunks of 1000 under the hood, provides LazyCollection API
User::where('active', false)->lazy(1000)->each->archive();
```

- cursor() uses a single database connection cursor for minimal RAM usage
- lazy() executes chunked subqueries under the hood and allows collection chaining
- Both prevent loading full datasets into PHP memory at once
