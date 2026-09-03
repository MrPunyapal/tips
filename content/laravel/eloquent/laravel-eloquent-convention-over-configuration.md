---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Architecture", "Clean Code"]
date: "2024-08-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Avoid Redundant Eloquent Overrides by Embracing Convention over Configuration

> Remove unnecessary $table, $primaryKey, and $timestamps properties from Eloquent models by adhering to standard Laravel conventions.

Declaring explicit overrides for standard default behaviors adds noise to Eloquent model definitions.

Laravel relies on convention over configuration: if your database table follows standard naming rules, zero configuration properties are required.

## Over-Configured Model (Anti-Pattern)

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // ❌ Redundant: Laravel already assumes table is 'articles'
    protected $table = 'articles';

    // ❌ Redundant: Laravel already assumes primary key is 'id'
    protected $primaryKey = 'id';

    // ❌ Redundant: Laravel already assumes auto-incrementing integer key
    public $incrementing = true;
    protected $keyType = 'int';

    // ❌ Redundant: Laravel already expects timestamps by default
    public $timestamps = true;
}
```

## Clean Eloquent Model (Recommended)

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
```

## When to Explicitly Override

Declare properties only when deviating from standard conventions (such as legacy database tables or UUID primary keys):

```php
class LegacyCustomer extends Model
{
    protected $table = 'tbl_customers_legacy';
    protected $primaryKey = 'customer_uuid';
    public $incrementing = false;
    protected $keyType = 'string';
}
```

## Summary

- Eliminates boilerplate properties from model definitions.
- Keeps model files focused on relationships, scopes, and casts.
- Declare properties only when integrating with non-standard legacy database schemas.
