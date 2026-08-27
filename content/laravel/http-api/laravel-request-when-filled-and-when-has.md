---
category: "Laravel"
tags: ["Laravel", "HTTP", "Clean Code", "Controllers"]
date: "2022-12-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Execute Closures on Input Presence with $request->whenFilled() and whenHas()

> Use $request->whenFilled() and $request->whenHas() to run closures only when input fields are present and non-empty.

Checking if optional search filters or form inputs are present in incoming requests usually requires manual `if ($request->filled('search')) { ... }` boilerplate.

Laravel's request object provides `whenFilled()` and `whenHas()` for fluent conditional execution.

## Using whenFilled()

`whenFilled()` executes the given closure only if the value exists in the request and is not empty:

```php
use Illuminate\Http\Request;

public function index(Request $request)
{
    $query = User::query();

    // Runs closure only if 'search' is present and not an empty string
    $request->whenFilled('search', function (string $search) use ($query) {
        $query->where('name', 'LIKE', "%{$search}%");
    });

    // Supports fallback default closure when the input is empty or absent
    $request->whenFilled('status', function (string $status) use ($query) {
        $query->where('status', $status);
    }, function () use ($query) {
        $query->where('status', 'active'); // Default fallback
    });

    return $query->paginate(20);
}
```

## whenFilled() vs. whenHas()

- **`whenHas('field', fn ($val) => ...)`**: Executes if the key is present in the request payload, even if its value is an empty string (`""`) or `null`.
- **`whenFilled('field', fn ($val) => ...)`**: Executes only if the key is present AND contains a non-empty, non-null value.

## Summary

- Replaces repetitive `if ($request->filled(...))` blocks with readable closures.
- Injects the resolved input value directly as the first argument to the callback.
- Supports optional default fallback callbacks as the second closure parameter.
