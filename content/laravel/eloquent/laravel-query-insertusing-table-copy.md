---
category: "Laravel"
tags: ["Laravel", "Database", "Performance"]
date: "2024-01-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Copy Datasets Between Tables Instantly with insertUsing()

> Use insertUsing() to execute INSERT INTO ... SELECT queries for copying data between database tables in SQL.

Fetching records into PHP memory and re-inserting them into another table via loops is slow. insertUsing() runs a single INSERT INTO ... SELECT query directly inside the database.

```php
use Illuminate\Support\Facades\DB;

// Fast database-level copy without loading records into PHP RAM
DB::table('archived_orders')->insertUsing(
    ['order_id', 'total', 'created_at'],
    DB::table('orders')->select('id', 'total', 'created_at')->where('status', 'completed')
);
```

- Executes high-speed INSERT INTO ... SELECT statements directly in database
- Avoids loading records into PHP memory buffers
- Ideal for data archiving, activity logging, and snapshot tables
