---
category: "Laravel"
tags: ["Laravel", "Strings", "Formatting", "Utilities"]
date: "2024-04-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Convert Variable Names and Slugs to Title Case with Str::headline()

> Use Str::headline() to convert camelCase, snake_case, or kebab-case strings into clean, human-readable title headings.

When generating automated table headers from database column names (like `user_profile_id`, `is_active`, or `stripeCustomerId`), `ucwords()` fails on underscores and camelCase boundaries.

`Str::headline()` converts identifiers into proper Title Case words.

## Basic Usage

```php
use Illuminate\Support\Str;

echo Str::headline('user_profile_id');
// Output: "User Profile Id"

echo Str::headline('stripeCustomerId');
// Output: "Stripe Customer Id"

echo Str::headline('send-welcome-email-job');
// Output: "Send Welcome Email Job"
```

## Generating Dynamic Table Headers in Blade

```blade
<thead>
    <tr>
        @foreach (['first_name', 'email_address', 'last_login_at'] as $column)
            <th>{{ str($column)->headline() }}</th>
        @endforeach
    </tr>
</thead>
```

## Summary

- Splits strings on underscores, dashes, and camelCase casing boundaries.
- Capitalizes each word for clean UI headers and labels.
- Fluent alternative via `str($text)->headline()`.
