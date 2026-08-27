---
category: "Laravel"
tags: ["Laravel", "Validation", "Security", "Authentication"]
date: "2023-04-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Centralize Application Password Security with Password::defaults()

> Define global password complexity requirements in AppServiceProvider to maintain consistent password rules across registration, reset, and profile forms.

Hardcoding password rules like `'password' => ['required', 'string', 'min:8', 'regex:...']` across multiple Form Requests makes it difficult to adjust security policies when compliance requirements change.

Laravel provides the `Illuminate\Validation\Rules\Password` rule object alongside `Password::defaults()` for centralized configuration.

## Defining Password Rules Globally

Configure the default password policy in `AppServiceProvider::boot()`:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(10)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(); // Checks HaveIBeenPwned API

            // Enforce stricter rules in production while keeping local dev fast
            return $this->app->isProduction()
                ? $rule
                : Password::min(8);
        });
    }
}
```

## Using Password::defaults() in Form Requests

In your registration and password reset validation rules:

```php
use Illuminate\Validation\Rules\Password;

public function rules(): array
{
    return [
        'password' => ['required', 'confirmed', Password::defaults()],
    ];
}
```

## Summary

- Centralizes password complexity in a single configuration point.
- Supports `uncompromised()` to reject passwords leaked in data breaches via k-Anonymity.
- Enables adjusting password requirements across the entire application without touching individual Form Requests.
