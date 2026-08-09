---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Macros"]
date: "2026-05-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Add a Reusable whereLike Macro for Eloquent Searching

> Simplify multi-column wildcard searches across model attributes by registering a clean whereLike macro on the Eloquent Builder.

Searching across multiple string columns usually requires repetitive orWhere chains. Registering a macro in AppServiceProvider provides a clean API for wildcard matching.

```php
use App\Models\User;

// Search across name, email, and bio in a single call
$users = User::whereLike(['name', 'email', 'bio'], $search)->get();
```

- Wraps multiple orWhere calls inside a grouped subquery clause
- Works with single column strings or arrays of columns
- Keeps controller search logic minimal and readable
