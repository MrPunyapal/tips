---
category: "Laravel"
tags: ["Laravel", "Validation", "Security", "Authentication"]
date: "2026-03-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Verify Existing User Credentials with the current_password Validation Rule

> Use the current_password validation rule to require users to confirm their existing password before performing sensitive account actions.

When a user changes their email address, updates their password, or modifies multi-factor authentication, verifying that they know their existing password prevents unauthorized modifications.

Laravel provides the built-in `current_password` validation rule.

## Using current_password in Form Requests

```php
public function rules(): array
{
    return [
        // Automatically validates input against auth()->user()->password
        'current_password' => ['required', 'current_password'],
        'new_password'     => ['required', 'min:8', 'confirmed'],
    ];
}
```

## Custom Authentication Guards

If validating credentials against a specific authentication guard (e.g. `admin` or `api`):

```php
'password' => ['required', 'current_password:admin'],
```

## Summary

- Compares input directly against the authenticated user's hashed password.
- Eliminates manual `Hash::check()` verification in controller methods.
- Essential protection for profile update forms and sensitive configuration settings.
