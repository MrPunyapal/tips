---
category: "Laravel"
tags: ["Laravel", "Collections", "Syntax"]
date: "2026-04-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Clean Up Collections with Higher-Order Collection Messages

> Use higher-order collection proxies like $users->each->archive() or $orders->sum->total to replace verbose closure callbacks.

Writing closures for single method invocations or attribute access across collections adds visual noise. Higher-order collection messages provide short property proxies on collections.

```php
use App\Models\User;

// BEFORE: Verbose closure
$users->each(function ($user) {
    $user->archive();
});
$total = $orders->sum(function ($order) {
    return $order->total;
});

// AFTER: Clean higher-order proxies
$users->each->archive();
$total = $orders->sum->total;
```

- Provides higher-order proxies for map, each, filter, reject, sum, and more
- Replaces single-line closure wrappers with property syntax
- Works with both Eloquent model methods and attribute names
