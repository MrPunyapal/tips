---
category: "Laravel"
tags: ["Laravel", "Database", "Queue"]
date: "2026-06-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Dispatch Jobs After Transaction Commit with DB::afterCommit()

> Use DB::afterCommit() to defer job dispatching until surrounding database transactions complete successfully.

Dispatching queue jobs inside active DB transactions can trigger race conditions where background workers run before the transaction commits. DB::afterCommit() delays side effects until commit finishes.

```php
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessPayment;

DB::transaction(function () use ($order) {
    $order->save();
    
    // Guarantees worker receives committed database records
    DB::afterCommit(fn () => ProcessPayment::dispatch($order));
});
```

- Prevents race conditions where queue workers query uncommitted database rows
- Discards callbacks automatically if transaction rolls back
- Can be set on jobs using public $afterCommit = true;
