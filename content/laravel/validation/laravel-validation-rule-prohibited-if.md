---
category: "Laravel"
tags: ["Laravel", "Validation", "Security", "Clean Code"]
date: "2023-11-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Reject Mutually Exclusive Inputs with Rule::prohibitedIf()

> Use Rule::prohibitedIf() and Rule::prohibited() to ensure specific fields cannot be submitted based on other form selections.

When building forms with mutually exclusive options (such as submitting a company tax ID when account type is 'individual', or submitting credit card details when payment method is 'paypal'), accepting invalid fields can cause billing bugs.

Laravel provides the `prohibited` and `prohibited_if` validation rules.

## Using Rule::prohibitedIf() in Form Requests

```php
use Illuminate\Validation\Rule;

public function rules(): array
{
    return [
        'account_type' => ['required', 'in:individual,business'],

        // Tax ID is strictly prohibited if account type is 'individual'
        'tax_id' => [
            Rule::prohibitedIf($this->account_type === 'individual'),
            'string',
            'max:20',
        ],

        'payment_method' => ['required', 'in:card,paypal'],

        // Card number cannot be submitted if payment method is 'paypal'
        'card_number' => [
            Rule::prohibitedIf($this->payment_method === 'paypal'),
            'nullable',
            'numeric',
        ],
    ];
}
```

## Summary

- Fails validation if prohibited fields are present in the request payload.
- Accepts boolean conditions or closure evaluations.
- Guarantees strict mutually exclusive input validation.
