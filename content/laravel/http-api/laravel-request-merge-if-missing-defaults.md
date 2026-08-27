---
category: "Laravel"
tags: ["Laravel", "HTTP", "Form Requests", "Clean Code"]
date: "2023-11-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Inject Default Values Safely with $request->mergeIfMissing()

> Use $request->mergeIfMissing() in Form Requests to set default fallback parameters without overwriting user-provided values.

When handling search queries, pagination limits, or optional boolean flags, calling `$request->merge()` overwrites existing values.

`mergeIfMissing()` injects default parameters only when the key is absent from the request payload.

## Using mergeIfMissing in Form Requests

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterProductsRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'per_page' => 15,
            'sort'     => 'latest',
            'status'   => 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'sort'     => ['in:latest,price_asc,price_desc'],
            'status'   => ['in:active,archived'],
        ];
    }
}
```

## Summary

- Sets fallback values only when keys do not exist in the request.
- Preserves explicit user inputs (including `false` or `0`).
- Keeps validation rules and controller actions clean and predictable.
