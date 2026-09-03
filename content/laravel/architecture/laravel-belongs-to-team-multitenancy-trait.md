---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Multi-Tenancy", "Architecture", "Security"]
date: "2023-11-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Multi-Tenant Team Scoping with a Reusable BelongsToTeam Model Trait

> Automatically assign tenant IDs and restrict Eloquent queries using a reusable BelongsToTeam model trait with model booting and global scopes.

In multi-tenant or team-based SaaS applications, models (such as projects, invoices, and documents) must belong to a specific team, and users must only access records belonging to their active team.

Instead of writing repetitive query scopes and `$model->team_id = auth()->user()->team_id` assignments in every controller, you can encapsulate multi-tenant behavior in a reusable model trait.

## The BelongsToTeam Trait

```php
namespace App\Traits\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTeam
{
    public static function bootBelongsToTeam(): void
    {
        // Automatically set the team_id when creating a new record
        static::creating(function ($model): void {
            if (auth()->check() && empty($model->team_id)) {
                $model->team_id = auth()->user()->team_id;
            }
        });
    }

    protected static function booted(): void
    {
        parent::booted();

        // Apply a global scope to filter queries by active team
        if (auth()->check()) {
            static::addGlobalScope('team', function (Builder $query): void {
                $query->team();
            });
        }
    }

    public function scopeTeam(Builder $query): Builder
    {
        return $query->when(
            auth()->user()?->team_id,
            function (Builder $query, int $teamId): Builder {
                // Prefix column with table name to prevent SQL ambiguity in joins
                return $query->where($this->getTable() . '.team_id', $teamId);
            },
            function (Builder $query): Builder {
                // If the user has no team, prevent data leakage
                abort(403, 'User does not belong to an active team.');
            }
        );
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
```

## Using the Trait on Models

```php
namespace App\Models;

use App\Traits\Models\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use BelongsToTeam;

    protected $fillable = ['name', 'description'];
}
```

## What This Automates

1. **Automatic Assignment**: Calling `Project::create(['name' => 'API v2'])` automatically populates `team_id` from the authenticated user.
2. **Automatic Query Scoping**: Calling `Project::all()` automatically generates `WHERE projects.team_id = ?`.
3. **Join Safety**: Using `$this->getTable() . '.team_id'` ensures joins and `hasManyThrough` relationships do not encounter "ambiguous column" SQL errors.

## Summary

- Use a `BelongsToTeam` trait to automate tenant ID assignment on creation and filter queries via global scopes.
- Table-prefix column references in query scopes to prevent SQL errors in joins.
- Eliminates manual scoping across controllers and guarantees tenant isolation.
