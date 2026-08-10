---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2026-07-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Use sole() Instead of firstOrFail() for Single Record Guarantees

> When you expect exactly one matching record, use sole() instead of firstOrFail(). It guards against multiple records by throwing MultipleRecordsFoundException.

When querying unique records, firstOrFail() silently returns the first record even if multiple records match due to data integrity issues. sole() makes sure exactly one record exists.

```php
// Throws ModelNotFoundException if 0, MultipleRecordsFoundException if 2+
$user = User::where('verification_token', $token)->sole();
```

- Asserts that exactly one record matches criteria
- Catches data integrity anomalies before bad states propagate
- Throws explicit MultipleRecordsFoundException
