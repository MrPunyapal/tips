---
category: "Laravel"
tags: ["Laravel", "Pint", "Blade", "Tooling"]
date: "2026-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Format Blade Templates with Laravel Pint v1.30.0

> Laravel Pint v1.30.0 adds native Blade template formatting via the `Pint/laravel_blade` rule, using Prettier and the Blade plugin under the hood.

Blade finally gets opinionated formatting in Pint, keeping your markup consistent without manual tweaking.

### CLI Formatting

```bash
# Format Blade templates alongside PHP files
./vendor/bin/pint --blade
```

### Persistent Configuration

```json
// pint.json
{
    "rules": {
        "Pint/laravel_blade": true
    }
}
```

Under the hood, Pint invokes Prettier with `prettier-plugin-blade` and `prettier-plugin-tailwindcss`. If the npm packages are missing, Pint detects your package manager and prompts for installation. Specific files (like Envoy tasks or certain mail views) are excluded by default.

- Requires Node.js (Prettier runs under the hood)
- Pint auto-detects your package manager (npm/yarn/pnpm/bun) for dependency installation
- Blade formatting is opinionated: consistent output, zero config debates
