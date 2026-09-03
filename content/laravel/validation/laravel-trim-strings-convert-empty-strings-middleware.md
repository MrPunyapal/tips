---
category: "Laravel"
tags: ["Laravel", "Middleware", "Validation"]
date: "2023-06-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Understand Input Trimming & Empty String Normalization

> Laravel automatically trims request strings and converts empty string inputs to null via default global middleware.

Laravel includes string trimming and empty string conversion middleware in its default global stack. Incoming string inputs are automatically trimmed of whitespace, and empty strings (`''`) are normalized to `null`.

---

## Default Behavior

```php
// Incoming request payload: ['name' => '  Punyapal  ', 'bio' => '']

$name = $request->input('name'); // 'Punyapal' (trimmed by TrimStrings)
$bio  = $request->input('bio');  // null (converted from '' by ConvertEmptyStringsToNull)
```

---

## Customizing Trimming in bootstrap/app.php

To prevent trimming for specific fields (such as passwords or formatted secret keys) or route patterns, configure exclusions via `trimStrings` in `bootstrap/app.php`:

```php
// bootstrap/app.php
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

->withMiddleware(function (Middleware $middleware) {
    $middleware->trimStrings(except: [
        'current_password',
        'password',
        'password_confirmation',
        fn (Request $request) => $request->is('webhooks/*'),
    ]);
})
```

---

## Key Points

- **Automatic Sanitization**: Strips accidental leading and trailing whitespace before requests reach controllers.
- **Null Safety**: Normalizes empty form inputs (`''`) into `null`, ensuring foreign keys and nullable database columns store clean null values.
- **Fluent Exclusions**: In Laravel 11+, `$middleware->trimStrings(except: ...)` accepts field names or route-matching closures.
