---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2026-07-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Use sole() Instead of firstOrFail() for Single Record Guarantees

> When you expect exactly one matching record, use `sole()` instead of `firstOrFail()`. It guards against multiple records by throwing `MultipleRecordsFoundException`.

When querying a unique record in Eloquent (like finding a user by a unique invitation token or email verification hash), developers often use `firstOrFail()`.

However, `firstOrFail()` silently returns the first record even if multiple records match due to a data integrity issue or missing database constraint.

### Better Practice: Use `sole()`

```php
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;

// Throws ModelNotFoundException if 0 records found
// Throws MultipleRecordsFoundException if 2+ records found
$user = User::where('verification_token', $token)->sole();
```

### Why this is safer:
- Expresses the true intent: you are asserting that **exactly one** record must match.
- Immediately exposes data anomalies in staging and production before bad states propagate.
