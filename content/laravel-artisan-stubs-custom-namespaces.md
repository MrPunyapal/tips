---
category: "Laravel"
tags: ["Laravel", "Artisan", "DX"]
date: "2024-03-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Customize Artisan Code Generators with stub:publish

> Publish and customize Artisan generator stubs using php artisan stub:publish to enforce custom code conventions across your team.

Standard php artisan make:controller or make:model commands generate default templates. Running stub:publish exports stub files to stubs/ so you can customize scaffolded code.

```bash
# Publish default Artisan code stubs to stubs/ directory
php artisan stub:publish
```

- Exports code stubs to stubs/ folder for custom modification
- Enforces team code standards (strict types, custom traits, imports)
- Automatically used by artisan make:* commands once published
