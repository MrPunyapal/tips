---
category: "Laravel"
tags: ["Laravel", "Validation", "Performance", "Security"]
date: "2025-06-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Stop Validating on First Attribute Failure with the bail Rule

> Add the bail rule to validation rules to stop executing subsequent expensive checks (like database queries) as soon as the first validation rule fails.

By default, Laravel validates an attribute against all specified rules. If an invalid email format is submitted, Laravel still runs expensive `unique:users,email` database queries on that invalid string, wasting database CPU cycles.

The `bail` rule stops validation on an attribute upon its first failure.

## Using bail in Form Requests

```php
public function rules(): array
{
    return [
        'email' => [
            'bail',                 // Stops on first error!
            'required',
            'email',
            'unique:users,email',   // Expensive SQL query ONLY runs if 'email' is valid!
        ],

        'account_id' => [
            'bail',
            'required',
            'numeric',
            'exists:accounts,id',   // Database check runs only if numeric
        ],
    ];
}
```

## Summary

- Prevents running expensive database `unique` and `exists` queries on malformed input.
- Returns only the first relevant validation error message to the user.
- Placed as the first rule in the attribute's rule array.
