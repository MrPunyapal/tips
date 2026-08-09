---
category: "Laravel"
tags: ["Laravel", "Middleware", "Validation"]
date: "2023-06-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Understand Input Trimming & Empty String Normalization

> Laravel automatically trims request strings and converts empty string inputs to null via default global middleware.

Laravel includes TrimStrings and ConvertEmptyStringsToNull middleware globally. Incoming string inputs are automatically trimmed of whitespace and empty strings ('') become null.

```php
// Request input: ['name' => '  Punyapal  ', 'bio' => '']

$name = $request->input('name'); // Returns 'Punyapal'
$bio  = $request->input('bio');  // Returns null
```

- TrimStrings removes leading and trailing whitespace automatically
- ConvertEmptyStringsToNull normalizes empty form inputs ('') to null
- Can be bypassed for specific fields (like passwords) via $except property
