---
category: "Laravel"
tags: ["Laravel", "Pagination", "Eloquent", "Collections"]
date: "2023-01-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Transform Paginated Records Without Breaking Links Using through()

> Use the through() method on paginators to transform item collections without losing pagination metadata and link generators.

When transforming paginated records (such as mapping models into lightweight view data or custom formatting), calling `$paginator->getCollection()->map(...)` mutates the underlying collection, while calling `$paginator->map(...)` returns a standard collection that discards pagination links and metadata.

The `through()` method transforms records in place while preserving the full paginator contract.

## Transforming Records with through()

```php
use App\Models\User;

public function index()
{
    $users = User::paginate(15)->through(function (User $user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'formatted_created_at' => $user->created_at->format('M d, Y'),
            'avatar_url' => $user->getAvatarUrl(),
        ];
    });

    // In Blade: $users->links() still works perfectly!
    return view('users.index', compact('users'));
}
```

## Difference Between map() and through()

- **`$paginator->map()`**: Returns an `Illuminate\Support\Collection` instance (destroys pagination links and total count).
- **`$paginator->through()`**: Returns the original `LengthAwarePaginator` with transformed items, preserving `$paginator->links()`, `$paginator->total()`, and `$paginator->currentPage()`.

## Summary

- Modifies items within paginated datasets in a clean single call.
- Preserves full pagination functionality (`links()`, `hasPages()`, `nextPageUrl()`).
- Ideal for preparing data for Inertia.js, API resources, and Blade tables.
