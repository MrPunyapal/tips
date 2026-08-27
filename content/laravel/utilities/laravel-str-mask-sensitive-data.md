---
category: "Laravel"
tags: ["Laravel", "Strings", "Security", "Utilities"]
date: "2022-10-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Mask Sensitive Information with Str::mask()

> Use Str::mask() to obfuscate portions of email addresses, phone numbers, and payment details for secure display in user interfaces.

When displaying sensitive user data (such as partially hidden email addresses or the last four digits of a credit card), writing manual `substr` slicing and regex masking functions is tedious and error-prone.

Laravel provides the fluent `Str::mask()` helper.

## Masking Email Addresses

```php
use Illuminate\Support\Str;

$email = 'punyapal@example.com';

// Masks characters starting at index 3, masking 5 characters with '*'
$masked = Str::mask($email, '*', 3, 5);
// Output: pun*****@example.com
```

## Masking Credit Cards and Phone Numbers

You can pass negative offsets to mask from the end of the string, keeping only the final digits visible:

```php
$cardNumber = '4532789123456789';

// Masks from index 0 through all but the last 4 characters
$maskedCard = Str::mask($cardNumber, '*', 0, -4);
// Output: ************6789
```

## Using Fluent String Syntax

```php
$phone = str('+15551234567')->mask('*', 2, -2);
// Output: +1*********67
```

## Summary

- Obfuscates portions of sensitive strings with custom masking characters (`*`, `#`, `X`).
- Supports positive and negative index offsets for flexible start and end positions.
- Available statically via `Str::mask()` and fluently via `str()->mask()`.
