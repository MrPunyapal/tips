---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Casts"]
date: "2024-01-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Combine Custom Casts and Enums for Complex Attributes

> Use Eloquent custom casts (CastsAttributes) to handle complex JSON serialization and Backed Enum arrays cleanly.

When model attributes contain complex JSON structures or collections of Enums, implement CastsAttributes to handle custom database transformation and object hydration.

```php
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Enums\Permission;

class PermissionCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): array
    {
        return array_map(fn ($val) => Permission::from($val), json_decode($value, true) ?? []);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode(array_map(fn ($enum) => $enum->value, $value));
    }
}
```

- Implements CastsAttributes with get() and set() transformation signatures
- Handles custom JSON encoding and object hydration transparently
- Keeps model classes free of manual JSON encoding logic
