---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Relationships", "Database", "Clean Code"]
date: "2026-08-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Scope Pivot Table Queries with Closures in wherePivot() in Laravel 13.26

> Laravel 13.26 allows wherePivot() and orWherePivot() to accept closures, enabling direct reuse of local scopes defined on custom Pivot models.

When querying `belongsToMany` relationships, filtering pivot columns traditionally required repeating raw column checks (`->wherePivot('is_active', true)->wherePivot('is_muted', false)`) wherever the relationship was called.

Laravel 13.26 enhances `wherePivot()` and `orWherePivot()` to accept closures, letting you invoke local scopes declared directly on your custom `Pivot` class.

---

## 1. Define Local Scopes on the Custom Pivot Model

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectSubscriber extends Pivot
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeNotMuted(Builder $query): Builder
    {
        return $query->where('is_muted', false);
    }
}
```

---

## 2. Chain Scopes Directly in wherePivot()

The closure receives a builder instance bound to your custom pivot model:

```php
// Project model relationship:
// $project->belongsToMany(User::class)->using(ProjectSubscriber::class);

// Query active, non-muted subscribers using pivot scopes
$subscribers = $project->subscribers()
    ->wherePivot(fn ($query) => $query->active()->notMuted())
    ->get();

// Also works directly with orWherePivot()
$eligible = $project->subscribers()
    ->wherePivot(fn ($query) => $query->active())
    ->orWherePivot(fn ($query) => $query->where('role', 'admin'))
    ->get();
```

---

## Key Benefits

- **Encapsulation**: Keeps pivot schema details inside the `Pivot` class rather than scattering raw column names throughout controllers.
- **Reusability**: Updating the definition of an "active" subscription updates all pivot queries across the entire application.
- **Backwards Compatible**: Standard scalar syntax (`wherePivot('is_active', true)`) remains fully supported for simple checks.
