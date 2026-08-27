---
category: "Laravel"
tags: ["Laravel", "Validation", "Arrays", "API"]
date: "2023-08-09"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Validate Nested Array Elements with Asterisk Wildcards

> Use asterisk wildcards (photos.*.url) in validation rules to validate every item inside nested arrays and collections.

When validating forms or API payloads containing lists of items (such as multiple invoice line items, photo galleries, or permission checkboxes), validating each nested item requires wildcard indexing.

Laravel supports the `*` wildcard syntax to apply rules across all array children.

## Validating Nested Items

```php
public function rules(): array
{
    return [
        // Ensure items is an array containing at least 1 element
        'items' => ['required', 'array', 'min:1'],

        // Validate each item's individual attributes
        'items.*.product_id' => ['required', 'exists:products,id'],
        'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        'items.*.discount' => ['nullable', 'numeric', 'min:0'],
    ];
}
```

## Customizing Array Validation Error Messages

Use `items.*.attribute` to define custom error messages:

```php
public function messages(): array
{
    return [
        'items.*.quantity.min' => 'Each line item must have a quantity of at least 1.',
        'items.*.product_id.exists' => 'One or more selected products are invalid.',
    ];
}
```

## Summary

- `array.*.field` validates fields across all elements in an array.
- `min:1` on the top-level array ensures the array is not empty.
- Automatically generates indexed error keys (e.g. `items.0.quantity`, `items.1.quantity`).
