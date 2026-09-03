---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Architecture"]
date: "2023-08-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Query Relationships Across Multiple Database Connections

> Query related models located on different database connections using whereHas() without cross-database join errors.

When your application partitions data across multiple database connections (such as separating analytics logs, multi-tenant tenants, or payment microservices onto different database servers), standard SQL joins between connection tables fail.

Eloquent's `whereHas()` directly handles relations across different database connections.

## Defining Models on Separate Connections

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $connection = 'main_db';

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}

class ActivityLog extends Model
{
    // Stored on a separate logging database server
    protected $connection = 'logging_db';
}
```

## Querying Across Connections with whereHas()

```php
use App\Models\Tenant;

// Eloquent handles the cross-connection query correctly
$activeTenants = Tenant::whereHas('activityLogs', function ($query) {
    $query->where('created_at', '>=', now()->subDays(7));
})->get();
```

## Summary

- Enables relational querying even when models reside on distinct database servers.
- Uses `WHERE EXISTS` queries scoped by database name.
- Eliminates manual cross-database ID querying loops.
