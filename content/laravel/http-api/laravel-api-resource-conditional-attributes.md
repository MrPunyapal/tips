---
category: "Laravel"
tags: ["Laravel", "API", "Security", "Clean Code"]
date: "2023-06-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Include Conditional Attributes in API Resources with when() and whenNotNull()

> Use when() and whenNotNull() inside API Resources to conditionally expose sensitive fields, admin metadata, and non-null properties.

When serializing models into API responses, certain fields (such as user email addresses, permission lists, or internal billing metadata) should only be exposed to administrators or owners.

Laravel API Resources provide conditional attribute helpers.

## Exposing Fields Based on User Permissions

```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,

            // Included only if the authenticated user is an administrator
            'email' => $this->when($request->user()?->is_admin, $this->email),
            'stripe_id' => $this->when($request->user()?->is_admin, $this->stripe_id),

            // Included only if not null
            'phone' => $this->whenNotNull($this->phone),

            // Merges multiple fields conditionally
            $this->mergeWhen($request->user()?->can('view-sensitive-data', $this->resource), [
                'created_ip' => $this->created_ip,
                'last_login_at' => $this->last_login_at,
            ]),
        ];
    }
}
```

## Summary

- `$this->when($bool, $val)` excludes keys completely when false.
- `$this->whenNotNull($val)` omits null attributes from JSON responses.
- `$this->mergeWhen($bool, [...])` conditionally merges multiple dictionary keys.
