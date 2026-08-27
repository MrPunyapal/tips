---
category: "Laravel"
tags: ["Laravel", "Validation", "Clean Code", "Architecture"]
date: "2023-07-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Create Reusable Validation Rules with the ValidationRule Interface

> Generate custom, reusable validation rule objects using php artisan make:rule with the Illuminate\Contracts\Validation\ValidationRule interface.

When complex validation logic (such as validating tax IDs, verifying phone carrier formats, or checking credit card checksums) is needed across multiple Form Requests, writing custom closures or regexes leads to duplicate code.

Laravel provides the `ValidationRule` contract for custom rule classes.

## Generating a Custom Rule

```bash
php artisan make:rule Uppercase
```

## Implementing the Rule Class

```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uppercase implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtoupper($value) !== $value) {
            $fail("The :attribute must be completely uppercase.");
        }
    }
}
```

## Using the Rule in Form Requests

```php
use App\Rules\Uppercase;

public function rules(): array
{
    return [
        'promo_code' => ['required', 'string', new Uppercase()],
    ];
}
```

## Summary

- Uses the modern single-method `validate($attribute, $value, $fail)` interface.
- Invoke `$fail('message')` to record validation failures.
- Highly testable and reusable across Form Requests and Validator instances.
