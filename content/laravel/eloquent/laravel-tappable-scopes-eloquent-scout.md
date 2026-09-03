---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Scout", "Architecture", "DRY"]
date: "2025-02-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Reusable Tappable Scopes for Eloquent and Laravel Scout Queries

> Extract query filtering logic into invokable scope classes that work interchangeably across Eloquent query builders and Laravel Scout search instances.

Standard Eloquent local scopes (`scopeActive()`, `scopeFilter()`) work well for database queries, but they are tightly coupled to the Eloquent `Builder` instance and cannot be directly invoked on a Laravel Scout search query builder (`Post::search()->...`).

By creating invokable "Tappable Scope" classes, you can share identical query constraints across both database queries and full-text search pipelines without duplicating logic.

## 1. Create the Invokable Scope Class

Create a dedicated scope class that accepts either an Eloquent `Builder` or a Scout `Builder`:

```php
namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use LaravelScoutBuilder as ScoutBuilder;

final class PublishedScope
{
    public function __construct(
        private ?User $user = null
    ) {}

    public function __invoke(Builder|ScoutBuilder $query): Builder|ScoutBuilder
    {
        return $query
            ->where('is_published', true)
            ->when($this->user, fn ($q) => $q->where('author_id', $this->user->id));
    }
}
```

## 2. Using in Model Local Scopes

Apply the scope class inside your model's local scope using `$query->tap()`:

```php
namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    public function scopePublished(Builder $query, ?User $user = null): Builder
    {
        return $query->tap(new PublishedScope($user));
    }
}
```

## 3. Using in Laravel Scout Search Queries

The same scope class can be piped directly into Scout search queries via `->tap()`:

```php
use App\Models\Post;
use App\Models\Scopes\PublishedScope;

// Search posts with the same publishing constraints applied
$results = Post::search($searchTerm)
    ->tap(new PublishedScope(auth()->user()))
    ->get();
```

## Why Use Tappable Scopes?

- **Dual Compatibility**: Operates directly across both standard Eloquent queries and Laravel Scout search pipelines.
- **Cross-Model Reuse**: Share identical filtering rules across multiple models (e.g. `Post`, `Article`, `Video`) without bloated traits.
- **Cleaner Models**: Keeps model files lightweight by extracting complex filtering criteria into dedicated, testable classes.

## Summary

- Use invokable scope classes paired with `->tap()` to share query logic between Eloquent and Scout.
- Keeps search filtering synchronized with database query scopes.
- Eliminates duplicate query logic and keeps model classes lean.
