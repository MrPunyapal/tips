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

In many-to-many Eloquent relationships (`belongsToMany`), pivot tables frequently store meaningful domain state (such as subscription statuses, membership roles, notification toggles, or expiration dates).

While Laravel has always supported filtering pivot columns using scalar values (such as `wherePivot('is_active', true)`), expressing multi-column business conditions across multiple controllers often leads to duplicated filtering logic.

**Laravel 13.26 enhances `wherePivot()` and `orWherePivot()` to accept a closure**, providing a builder instance that can invoke local scopes defined directly on your custom pivot model.

---

## 1. The Traditional Approach: Repeated Scalar Conditions

Consider a `Project` model with a many-to-many `subscribers` relationship:

```php
// Project.php
public function subscribers(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'project_subscribers')
        ->using(ProjectSubscriber::class)
        ->withPivot(['is_active', 'is_muted', 'role']);
}
```

Prior to Laravel 13.26, filtering active, unmuted subscribers required repeating raw column checks wherever the relationship was queried:

```php
// Filtering active, non-muted subscribers
$activeSubscribers = $project->subscribers()
    ->wherePivot('is_active', true)
    ->wherePivot('is_muted', false)
    ->get();
```

While functional, this approach scatters internal pivot schema details across your application and duplicates logic whenever those business rules are queried.

---

## 2. Defining Scopes on the Custom Pivot Model

With custom pivot models extending `Illuminate\Database\Eloquent\Relations\Pivot`, you can encapsulate business rules as standard Eloquent local scopes:

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

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }
}
```

---

## 3. Laravel 13.26: Passing Closures to `wherePivot()`

In Laravel 13.26, you can pass a closure to `wherePivot()`. The closure receives a builder scoped to the custom pivot model, allowing you to chain your pivot model's local scopes directly:

```php
// Query using custom pivot scopes directly
$subscribers = $project->subscribers()
    ->wherePivot(fn ($query) => $query
        ->active()
        ->notMuted()
    )
    ->get();
```

### Before vs. After

- **Before**: Relationship queries explicitly specify raw column conditions (`->wherePivot('is_active', true)->wherePivot('is_muted', false)`).
- **After**: Relationship queries express domain intent (`->wherePivot(fn ($q) => $q->active()->notMuted())`), while the implementation rules reside inside the `ProjectSubscriber` pivot class.

---

## 4. Reusing Pivot Scopes Across Queries

Because the query logic lives inside the custom pivot class, changes to what constitutes an "active" subscription (such as adding an expiration check) only need to be updated in one place:

```php
// Active subscribers
$active = $project->subscribers()
    ->wherePivot(fn ($query) => $query->active())
    ->get();

// Active admins
$admins = $project->subscribers()
    ->wherePivot(fn ($query) => $query->active()->admins())
    ->get();
```

---

## 5. Companion Support in `orWherePivot()`

The same closure capability is available on `orWherePivot()` for alternative conditions:

```php
$eligibleUsers = $project->subscribers()
    ->wherePivot(fn ($query) => $query->active())
    ->orWherePivot(fn ($query) => $query->admins())
    ->get();
```

---

## What This Feature Is Not

- `wherePivot()` itself is not new; Laravel 13.26 simply adds closure and pivot-scope resolution.
- It does not replace custom pivot models, but instead gives them greater utility within relationship chains.
- Scalar `wherePivot('column', $value)` syntax remains fully supported for simple one-off checks.

---

## Summary

- Laravel 13.26 allows `wherePivot()` and `orWherePivot()` to accept closures.
- Closures receive a builder instance configured for the relationship's custom pivot model.
- Encapsulates pivot filtering logic into reusable local scopes on the pivot class instead of repeating raw column checks.
