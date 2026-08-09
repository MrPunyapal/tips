---
category: "Laravel"
tags: ["Laravel", "Collections", "Performance"]
date: "2026-07-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Stop Using get() Before Collection Chains: Use lazy()

> Calling get() loads all matching rows into memory before filtering. Use lazy() to process database records lazily.

Chaining collection methods like filter() or map() after get() loads the entire dataset into RAM first. Using lazy() streams database records through collection operations on demand.

```php
use App\Models\Order;

// BAD: Loads 500,000 orders into RAM before filtering
$expensive = Order::get()->filter(fn ($o) => $o->calculateTotal() > 1000);

// GOOD: Streams records through filter lazily without RAM exhaustion
$expensive = Order::lazy()->filter(fn ($o) => $o->calculateTotal() > 1000);
```

- get() executes SELECT * and instantiates all models immediately
- lazy() fetches records in chunks as collection pipeline elements demand
- Slashes RAM usage when applying complex collection filters to large datasets
