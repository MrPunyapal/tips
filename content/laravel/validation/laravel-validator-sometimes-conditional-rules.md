---
category: "Laravel"
tags: ["Laravel", "Validation", "Clean Code"]
date: "2023-05-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Apply Conditional Validation with Validator::sometimes()

> Use Validator::sometimes() to apply validation rules only when a specific input field is present or when a custom closure returns true.

When validating API endpoints or multi-step checkout forms, certain validation rules should only trigger when specific fields are present in the request payload or when particular business conditions are met.

## Validating Fields Only When Present

Using `sometimes` as a rule string tells Laravel to only validate the field if it exists in the input array:

```php
$request->validate([
    'email' => 'required|email',
    'password' => 'required|min:8',
    // Only validated if 'avatar' is present in request
    'avatar' => 'sometimes|image|max:2048',
]);
```

## Dynamic Conditionals via Closure

To validate a field based on dynamic runtime logic, invoke `sometimes()` on the validator instance:

```php
use IlluminateSupportFacadesValidator;
use IlluminateValidationValidator as ValidatorInstance;

$validator = Validator::make($request->all(), [
    'email' => 'required|email',
    'channel' => 'required|in:email,sms',
]);

// Require phone_number only when channel is 'sms'
$validator->sometimes('phone_number', 'required|digits:10', function ($input) {
    return $input->channel === 'sms';
});
```

## Summary

- `sometimes` rule string runs validation only when the attribute key exists in the input array.
- `Validator::sometimes('field', 'rules', fn ($input) => ...)` evaluates runtime logic to determine rule application.
- Ideal for flexible API PATCH endpoints and conditional checkout workflows.
