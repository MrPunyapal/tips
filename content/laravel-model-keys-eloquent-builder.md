---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Use modelKeys() on the Eloquent Builder in Laravel 13.24

> Laravel 13.24 adds `modelKeys()` directly to the Eloquent query builder, replacing hardcoded `pluck('id')` calls with a method that automatically uses the model's configured primary key.

### Replacing pluck()

```php
use App\Models\User;

// Before: hardcoded column name
$ids = User::where('active', true)->pluck('id');

// After: respects the model's primary key automatically
$ids = User::where('active', true)->modelKeys();
```

### Working with Custom Primary Keys

```php
class Order extends Model
{
    protected $primaryKey = 'order_uuid';
}

// Automatically plucks 'order_uuid': no hardcoded column names
$keys = Order::where('status', 'shipped')->modelKeys();
```

- Replaces `pluck('id')` with a model-aware alternative
- Automatically respects custom `$primaryKey` definitions
- Previously only available on Eloquent Collections, now works on the query builder
