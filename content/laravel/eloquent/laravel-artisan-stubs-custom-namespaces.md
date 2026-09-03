---
category: "Laravel"
tags: ["Laravel", "Artisan", "DX"]
date: "2024-03-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Customize Artisan Code Generators with stub:publish

> Publish and customize Artisan generator stubs using php artisan stub:publish to enforce custom code conventions across your team.

Standard `php artisan make:controller` or `make:model` commands generate default framework templates.

Running `stub:publish` exports these blueprint files to a local `stubs/` directory, allowing you to enforce project-specific conventions automatically.

---

## 1. Publish Stubs

```bash
php artisan stub:publish
```

This exports all generator stubs into your application root's `stubs/` directory.

---

## 2. Customize Templates (e.g. stubs/model.stub)

Edit any stub file to enforce strict types, default properties, or custom traits:

```php
<?php

namespace {{ namespace }};

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {{ class }} extends Model
{
    use HasFactory;

    // Enforce un-guarded models by default across the team
    protected $guarded = [];
}
```

---

## Key Benefits

- **Team Consistency**: Every developer running `make:model` or `make:controller` receives the standardized company template.
- **Zero Friction**: Automatically picked up by Artisan without requiring any custom command flags or configuration files.
