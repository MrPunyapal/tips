---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Macros", "Search", "Database"]
date: "2023-10-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Reusable whereLike Macro with Relationship and Expression Support

> Simplify multi-column wildcard searches across model columns, relationships, and raw SQL expressions using a powerful whereLike macro on the Eloquent Builder.

Searching across multiple attributes frequently leads to verbose and repetitive `orWhere` query chains in controller code.

By registering a custom `whereLike` macro on Eloquent's `Builder`, you can search across model columns, dot-notation relationships (`user.name`), and custom database expressions (`DB::raw()`) in a single readable call.

## Registering the Macro in AppServiceProvider

```php
namespace App\Providers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            return $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        // Check if attribute is a relationship dot-notation string (e.g. 'user.name')
                        ! ($attribute instanceof Expression) && str_contains((string) $attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relation, $relatedAttribute] = explode('.', (string) $attribute);

                            $query->orWhereHas($relation, function (Builder $query) use ($relatedAttribute, $searchTerm) {
                                $query->where($relatedAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            // Search on local column or DB::raw expression
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
        });
    }
}
```

## Usage Example

You can pass single columns, related model fields using dot notation, and formatted raw SQL expressions:

```php
use App\Models\Post;
use Illuminate\Support\Facades\DB;

$search = request('search');

$posts = Post::query()
    ->whereLike([
        'title',
        'description',
        'user.name',
        'user.email',
        DB::raw('CONCAT(user.first_name, " ", user.last_name)'),
        DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'),
    ], $search)
    ->with('user')
    ->paginate(15);
```

## How It Works

1. **Encapsulated Scope**: Wraps the entire search clause in a single `$this->where(function ($query) ...)` closure so that boolean `AND` / `OR` operator precedence is respected when chained with other query filters.
2. **Relationship Detection**: Automatically detects dot notation (`'user.name'`) and applies `orWhereHas` subqueries against the related model.
3. **Expression Compatibility**: Supports `DB::raw()` expressions without throwing type errors, enabling searches on computed columns and SQL date formats.

## Summary

- Use `Builder::macro('whereLike')` to consolidate multi-attribute wildcard searches into a single expressive method.
- Automatically handles both local table columns and related model attributes via `orWhereHas`.
- Respects SQL operator grouping and supports raw database expressions.
