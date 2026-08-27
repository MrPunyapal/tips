---
category: "Laravel"
tags: ["Laravel", "Security", "Strings", "Utilities"]
date: "2024-03-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Generate Secure Random Passwords with Str::password()

> Use Str::password() to generate cryptographically secure random passwords with customizable length and character constraints.

Generating random temporary passwords for user onboarding or password resets using random string helpers often produces strings that fail common password complexity rules (e.g. missing numbers or symbols).

`Str::password()` generates strong, cryptographically secure passwords.

## Basic Usage

```php
use Illuminate\Support\Str;

// Generates a secure 32-character password with letters, numbers, and symbols
$password = Str::password();
// Output: "k8#N!x9$mP2@vL5*qR7&wT1^yB4%zC6"
```

## Custom Length and Character Rules

Control password length and character requirements with named parameters:

```php
$tempPassword = Str::password(
    length: 16,
    letters: true,
    numbers: true,
    symbols: true,
    spaces: false
);
```

## In User Creation Workflows

```php
$user = User::create([
    'name'     => $request->name,
    'email'    => $request->email,
    'password' => Hash::make($plainTextPassword = Str::password(16)),
]);

$user->notify(new SendTemporaryPasswordNotification($plainTextPassword));
```

## Summary

- Generates cryptographically secure random passwords via PHP's random_int engine.
- Configurable length, symbols, numbers, and spaces.
- Built-in to Laravel's `Str` and `str()` helper.
