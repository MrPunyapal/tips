---
category: "Laravel"
tags: ["Laravel", "Artisan", "DX", "Architecture"]
date: "2026-06-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Scaffold Actions, Builders, and Collections with Artisan

> Default Artisan stubs don't cover common domain patterns like Actions, Custom Query Builders, or Collections. Extend your generator commands to keep app structure consistent.

As Laravel applications grow, pushing business logic into Action classes or custom Query Builders keeps controllers and models skinny.

Instead of creating files manually, use generator commands to scaffold these structural patterns.

### Scaffolding Custom Query Builders

```bash
# Generate a dedicated Query Builder class for a model
php artisan make:builder UserBuilder
```

```php
namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function active(): self
    {
        return $this->where('status', 'active');
    }
}
```

### Scaffolding Invokable Action Classes

```bash
# Generate a clean, single-responsibility Action class
php artisan make:action CreateOrderAction
```

```php
namespace App\Actions;

class CreateOrderAction
{
    public function handle(array $data): Order
    {
        // Business logic here
    }
}
```

- Custom Builders encapsulate complex query scopes away from models
- Action classes provide single-responsibility execution for business operations
- Keeps directory structure predictable across team members
