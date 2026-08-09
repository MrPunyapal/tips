---
category: "Laravel"
tags: ["Laravel", "Pint", "Blade"]
date: "2026-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Format Blade Templates with Laravel Pint v1.30.0

> Laravel Pint v1.30.0 adds native Blade template formatting via the Pint/laravel_blade rule, using Prettier under the hood.

Blade gets opinionated formatting in Pint, keeping your markup consistent without manual tweaking.

```bash
./vendor/bin/pint --blade
```

- Requires Node.js (Prettier runs under the hood)
- Pint auto-detects package manager for dependency installation
- Blade formatting is opinionated: consistent output, zero config debates
