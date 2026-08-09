---
category: "Laravel"
tags: ["Laravel", "Artisan", "DX"]
date: "2026-06-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Scaffold Actions, Builders, and Collections with Artisan

> Extend your generator commands to scaffold common domain patterns like Actions, Custom Query Builders, or Collections.

Pushing business logic into Action classes or custom Query Builders keeps controllers and models skinny. Use generator commands to scaffold these structural patterns instantly.

```bash
php artisan make:builder UserBuilder
php artisan make:action CreateOrderAction
```

- Custom Builders encapsulate complex query scopes away from models
- Action classes provide single-responsibility execution for business operations
- Keeps directory structure predictable across team members
