---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Use modelKeys() on the Eloquent Builder in Laravel 13.24

> Laravel 13.24 adds modelKeys() directly to the Eloquent query builder, replacing hardcoded pluck('id') calls with a method that uses the model primary key.

Instead of hardcoding column strings like pluck('id'), calling modelKeys() on the query builder automatically respects custom primary key configurations defined on the target model.

```php
use App\Models\User;

// Replaces User::where('active', true)->pluck('id')
$ids = User::where('active', true)->modelKeys();
```

- Replaces pluck('id') with a model-aware alternative
- Automatically respects custom $primaryKey definitions
- Previously only available on Eloquent Collections, now works on the builder
