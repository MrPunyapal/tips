---
category: "Laravel"
tags: ["Laravel", "Validation", "Form Requests", "Architecture"]
date: "2023-05-03"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Add Complex Business Checks with FormRequest::after() Hooks

> Attach after-validation callback hooks inside Form Requests to execute custom multi-field checks after standard validation rules pass.

While standard validation rules handle type and constraint checks on individual attributes, complex cross-field business logic (such as checking account balances or verifying third-party inventory) often ends up cluttering controller methods.

Form Requests support the `after()` method to attach custom callbacks directly to the underlying validator.

## Adding an after() Hook

Define an `after()` method returning an array of callables in your Form Request:

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TransferFundsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Runs only after standard rules pass
                if ($this->user()->balance < $this->amount) {
                    $validator->errors()->add(
                        'amount',
                        'Insufficient account balance to complete this transfer.'
                    );
                }

                if ($this->user()->id === (int) $this->recipient_id) {
                    $validator->errors()->add(
                        'recipient_id',
                        'You cannot transfer funds to yourself.'
                    );
                }
            }
        ];
    }
}
```

## Summary

- Executes after standard syntax and type rules pass successfully.
- Adds errors directly to the `$validator->errors()` bag using standard form error keys.
- Keeps controller methods clean and focused entirely on business fulfillment.
