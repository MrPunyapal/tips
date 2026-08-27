---
category: "Laravel"
tags: ["Laravel", "Validation", "Form Requests", "Clean Code"]
date: "2023-03-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Apply Conditional Validation Rules Cleanly with Rule::when() and Rule::excludeIf()

> Use Rule::when() and Rule::excludeIf() to apply dynamic validation rules without writing fragmented if-else statements.

Complex forms often require fields to be validated or omitted based on other input values (such as requiring a tax ID only for business accounts, or excluding shipping fields for digital items).

Instead of manipulating rule arrays conditionally with nested `if` blocks, Laravel provides declarative rule helpers.

## Conditional Validation with Rule::when()

```php
use Illuminate\Validation\Rule;

public function rules(): array
{
    return [
        'account_type' => ['required', 'in:individual,business'],

        // Required and verified only if account_type is 'business'
        'tax_id' => [
            Rule::when(
                $this->account_type === 'business',
                ['required', 'string', 'max:20'],
                ['nullable', 'string']
            ),
        ],
    ];
}
```

## Excluding Fields from Validated Output with Rule::excludeIf()

When optional fields are submitted but irrelevant (such as credit card details when paying with PayPal), `Rule::excludeIf()` strips the field from `$request->validated()`:

```php
use Illuminate\Validation\Rule;

public function rules(): array
{
    return [
        'payment_method' => ['required', 'in:card,paypal'],

        // Completely excluded from $request->validated() when payment_method is paypal
        'card_number' => [
            Rule::excludeIf($this->payment_method === 'paypal'),
            'required',
            'numeric',
        ],
    ];
}
```

## Summary

- `Rule::when($condition, $rules, $defaultRules)` swaps rules dynamically based on booleans or closures.
- `Rule::excludeIf($condition)` omits fields from the final validated data payload when true.
- Keeps form request classes declarative and easy to read.
