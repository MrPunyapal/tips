---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Casts"]
date: "2023-06-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Dynamically Append Model Casts with mergeCasts()

> Use mergeCasts() to dynamically append attribute casting rules to Eloquent model instances at runtime.

When working with dynamic attributes or traits on models, hardcoding all casts in $casts can be inflexible. Use mergeCasts() to add casting definitions dynamically.

```php
use App\Models\User;

$user = new User();
$user->mergeCasts([
    'options' => 'array',
    'verified_at' => 'datetime',
]);
```

- Appends cast rules to existing model casts dynamically at runtime
- Ideal for reusable model traits that require specific attribute casts
- Does not overwrite existing cast declarations for un-specified attributes
