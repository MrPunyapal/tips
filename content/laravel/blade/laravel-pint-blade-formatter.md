---
category: "Laravel"
tags: ["Laravel", "Pint", "Blade"]
date: "2026-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Format Blade Templates with Laravel Pint v1.30.0

> Laravel Pint v1.30.0 adds native Blade template formatting via the Pint/laravel_blade rule, using Prettier under the hood.

Laravel Pint can format your `.blade.php` views alongside PHP files, keeping template indentation and directive styling consistent across your team.

---

## 1. Format via CLI

Format Blade files on demand with the `--blade` flag:

```bash
./vendor/bin/pint --blade
```

---

## 2. Enable Permanently in pint.json

To make Blade formatting the default whenever `pint` runs (including in CI pipelines), enable the rule in `pint.json`:

```json
{
    "rules": {
        "Pint/laravel_blade": true
    }
}
```

---

## Requirements & Mechanics

- **Prettier Engine**: Pint invokes Prettier under the hood. Node.js must be available on the machine.
- **Auto-Installation**: Pint auto-detects your package manager (`npm`, `pnpm`, `yarn`, `bun`) to install necessary Prettier dependencies when first run.
- **Zero Config Arguments**: Opinionated defaults format loops, components, and directives consistently without configuration arguments.
