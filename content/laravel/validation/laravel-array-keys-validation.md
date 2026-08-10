---
category: "Laravel"
tags: ["Laravel", "Validation"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Restrict Array Keys with the array_keys Validation Rule in Laravel 13.24

> Laravel 13.24 adds the array_keys validation rule to reject any unexpected keys in an array input, keeping input strictly limited to allowed keys.

When building API endpoints that receive settings or configuration objects, unexpected extra keys could indicate tampering or version mismatches. The array_keys rule restricts input to allowed keys only.

```php
use Illuminate\Support\Facades\Validator;

$validator = Validator::make($request->all(), [
    'settings'   => 'required|array|array_keys:theme,language,timezone',
    'settings.*' => 'string',
]);
```

- array_keys: rejects keys NOT in your list (whitelist)
- required_array_keys: checks that specific keys ARE present (required check)
- Rejects payloads containing unlisted keys
