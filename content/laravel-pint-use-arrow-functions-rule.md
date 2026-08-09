---
category: "Laravel"
tags: ["Laravel", "Pint", "Code Quality"]
date: "2025-08-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Automate Short Closure Conversions with Laravel Pint

> Enable the use_arrow_functions rule in pint.json to automatically refactor single-line closures into arrow functions.

Manually converting single-line function () use ($var) callbacks to fn () arrow functions across codebases takes time. Laravel Pint's use_arrow_functions rule automates this conversion.

```json
// pint.json
{
    "rules": {
        "use_arrow_functions": true
    }
}
```

- Converts single-line closures to short arrow functions fn () automatically
- Auto-captures outer scope variables without explicit use () bindings
- Enforces modern short closure conventions across your codebase
