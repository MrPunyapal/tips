---
category: "Laravel"
tags: ["Laravel", "Validation"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Restrict Array Keys with the array_keys Validation Rule in Laravel 13.24

> Laravel 13.24 adds the `array_keys` validation rule to reject any unexpected keys in an array input, keeping input strictly limited to allowed keys.

When building API endpoints that receive settings or configuration objects, unexpected extra keys could indicate tampering or version mismatches. The `array_keys` rule restricts input to allowed keys only.

### Usage Example

```php
use Illuminate\Support\Facades\Validator;

$validator = Validator::make($request->all(), [
    'settings'   => 'required|array|array_keys:theme,language,timezone',
    'settings.*' => 'string',
]);

// ✅ Passes: ['settings' => ['theme' => 'dark', 'language' => 'en']]
// ❌ Fails:  ['settings' => ['theme' => 'dark', 'admin' => true]]
//            → 'admin' key is not in the allowed list
```

- `array_keys`: rejects keys NOT in your list (whitelist)
- `required_array_keys`: checks that specific keys ARE present (required check)
