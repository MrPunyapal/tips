---
category: "Laravel"
tags: ["Laravel", "Collections", "Best Practices"]
date: "2026-07-02"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Cache"
---

# Avoid map() When Not Transforming: Use each()

> Use each() for iteration side effects like sending emails; use map() exclusively when returning a transformed collection.

Using map() purely for side effects builds an unused array in memory. Use each() to communicate intent when performing actions without modifying the collection elements.

```php
// BAD: Builds a useless array of nulls in memory
$users->map(function ($user) {
    $user->notify(new MonthlyReport());
});

// GOOD: Expresses iteration intent clearly without allocating memory
$users->each(function ($user) {
    $user->notify(new MonthlyReport());
});
```

- map() constructs and returns a new transformed collection instance
- each() performs side-effect actions and returns the original collection
- Keeps memory footprint lower and code intent explicit
