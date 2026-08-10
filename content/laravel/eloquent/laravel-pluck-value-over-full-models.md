---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Performance"]
date: "2026-06-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Don't Load Full Models for Single Columns: Use pluck() or value()

> Use value() for single scalar values and pluck() for single-column arrays instead of instantiating full Eloquent models.

Querying full Eloquent model instances just to read a single attribute like an email or name wastes CPU and memory. Use value() or pluck() to execute optimized database queries.

```php
use App\Models\User;

// BAD: Instantiates entire User Eloquent model into RAM
$email = User::where('id', $id)->first()?->email;

// GOOD: Executes SELECT email LIMIT 1 and returns scalar string
$email = User::where('id', $id)->value('email');

// GOOD: Returns flat array of emails directly from database
$emails = User::where('active', true)->pluck('email');
```

- value('column') returns a single scalar value directly from database
- pluck('column') returns a flat array or collection of values
- Avoids model hydration overhead and reduces database payload transfer
