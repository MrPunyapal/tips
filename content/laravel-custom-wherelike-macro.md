---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Macros"]
date: "2026-05-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Add a Reusable whereLike Macro for Eloquent Searching

> Simplify multi-column wildcard searches across model attributes by registering a clean `whereLike` macro on the Eloquent Builder.

Searching across multiple string columns usually requires repetitive `orWhere('column', 'LIKE', "%{$search}%")` chains.

Adding a simple macro in your `AppServiceProvider` provides a clean API for full-text wildcard matching across arrays of attributes.

### Registering the Macro

```php
namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                }
            });

            return $this;
        });
    }
}
```

### Usage in Controllers or Queries

```php
// Search across name, email, and bio in a single call
$users = User::whereLike(['name', 'email', 'bio'], $search)->get();
```

- Wraps multiple `orWhere` calls inside a grouped subquery clause
- Works with single column strings or arrays of columns
- Keeps controller search logic minimal and readable
