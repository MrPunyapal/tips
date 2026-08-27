---
category: "Laravel"
tags: ["Laravel", "Validation", "Form Requests", "Security"]
date: "2023-03-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Sanitize and Transform Input Before Validation with prepareForValidation()

> Use prepareForValidation() inside Form Request classes to trim, normalize, cast, or pre-format request inputs before validation rules execute.

Validation rules evaluate incoming request data in its raw state. When users submit phone numbers with formatting, slugs with spaces, or messy email addresses, normalizing input *before* validation runs ensures cleaner validation checks and prevents duplicate sanitization in controllers.

## Normalizing Input in Form Requests

Override `prepareForValidation()` in your Form Request:

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            // Normalize email to lowercase
            'email' => strtolower(trim($this->email ?? '')),

            // Strip non-digit characters from phone number
            'phone' => preg_replace('/\D/', '', $this->phone ?? ''),

            // Generate slug if not provided
            'slug' => str($this->slug ?? $this->title)->slug()->value(),
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'digits:10'],
            'slug' => ['required', 'string', 'unique:users,slug'],
        ];
    }
}
```

## Accessing Merged Data

Because `$this->merge()` updates the request payload prior to validation, calling `$request->validated()` inside your controller receives the clean, sanitized data directly.

## Summary

- Modifies request inputs before validation rules execute.
- Keeps controller actions free from defensive formatting and string cleaning logic.
- Guarantees that `$request->validated()` contains sanitized, normalized values.
