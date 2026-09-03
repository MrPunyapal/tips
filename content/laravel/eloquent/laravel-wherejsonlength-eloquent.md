---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2024-01-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Filter Records by JSON Array Size with whereJsonLength()

> Use whereJsonLength() to query database records based on the number of elements in a JSON array attribute.

Filtering records by the number of elements stored inside a JSON array column is natively supported in Eloquent using `whereJsonLength()`.

---

## Code Examples

```php
use App\Models\User;

// 1. Find users with an empty tags array (length = 0)
$untaggedUsers = User::whereJsonLength('preferences->tags', 0)->get();

// 2. Select users with more than 3 active notification channels
$multiChannelUsers = User::whereJsonLength('settings->channels', '>', 3)->get();

// 3. Chain with orWhereJsonLength
$filtered = User::whereJsonLength('skills', '>=', 5)
    ->orWhereJsonLength('certifications', '>', 0)
    ->get();
```

---

## Key Benefits

- **Native SQL JSON Functions**: Leverages `JSON_LENGTH()` in MySQL and `jsonb_array_length()` in PostgreSQL under the hood.
- **Comparison Operators**: Supports all standard comparison operators (`=`, `>`, `<`, `>=`, `<=`).
- **Cross-Database**: Works uniformly across MySQL, PostgreSQL, and SQLite.
