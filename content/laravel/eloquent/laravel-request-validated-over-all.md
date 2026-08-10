---
category: "Laravel"
tags: ["Laravel", "Security", "Validation"]
date: "2026-06-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Never Save $request->all(): Use $request->validated()

> Always pass $request->validated() or $request->safe() into model creation methods to prevent mass-assignment vulnerabilities.

Passing $request->all() directly into Model::create() exposes applications to mass assignment vulnerabilities if unfillable or un-sanitized fields are submitted in HTTP request payloads.

```php
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

public function store(StoreUserRequest $request)
{
    // ❌ UNSAFE: Passes raw request keys including hidden payload injection
    // User::create($request->all());

    // ✅ SAFE: Passes only explicitly validated fields
    $user = User::create($request->validated());
}
```

- validated() filters HTTP input down to explicitly defined validation rules
- Prevents malicious form input keys from modifying un-fillable model attributes
- Pair with FormRequest classes for clean controller separation
