---
category: "Laravel"
tags: ["Laravel", "Authorization", "Gates", "Security"]
date: "2023-12-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Grant Global Super Admin Permissions with Gate::before()

> Use Gate::before() in AppServiceProvider to grant master administrator privileges across all policies and authorization checks.

In applications with strict role-based access control, Super Admins often require universal access across all modules without having to add `if ($user->isSuperAdmin()) return true;` inside every policy method.

`Gate::before()` intercepts all gate and policy evaluations before individual policy methods are executed.

## Configuring Gate::before in AppServiceProvider

```php
namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            // Return true to grant access immediately
            if ($user->is_super_admin) {
                return true;
            }

            // Return null to fall through to standard policy checks
            return null;
        });
    }
}
```

## Important: Return null, Not false

- **Returning `true`**: Immediately authorizes the user, bypassing all policies.
- **Returning `null`**: Passes evaluation to the matching Policy or Gate.
- **Returning `false`**: Immediately denies the user, blocking regular policy checks even if the user would otherwise be authorized.

## Summary

- Centralizes Super Admin overrides in a single callback.
- Eliminates repetitive admin checks inside individual Policy classes.
- Always return `null` for non-admin users to allow standard policy logic to run.
