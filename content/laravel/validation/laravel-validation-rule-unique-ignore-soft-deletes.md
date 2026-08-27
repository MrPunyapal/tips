---
category: "Laravel"
tags: ["Laravel", "Validation", "Database", "Security"]
date: "2023-04-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Ignore Current Models and Soft Deletes in Rule::unique() Validation

> Configure Rule::unique() to ignore existing model IDs during updates and exclude soft-deleted records from uniqueness checks.

When validating uniqueness during an update operation, failing to ignore the current model's ID causes the validator to flag the user's existing email as already taken. Additionally, if soft deletes are enabled, soft-deleted rows can prevent active users from using a reclaimed email address.

## Ignoring Current Model on Update

Pass the model instance or primary key to `->ignore()`:

```php
use App\Models\User;
use Illuminate\Validation\Rule;

public function rules(): array
{
    /** @var User $user */
    $user = $this->route('user');

    return [
        'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($user),
        ],
    ];
}
```

## Exclude Soft-Deleted Records with withoutTrashed()

By default, database uniqueness checks include soft-deleted records. Use `withoutTrashed()` to allow users to register with an email that belonged to a deleted account:

```php
use Illuminate\Validation\Rule;

public function rules(): array
{
    return [
        'username' => [
            'required',
            'string',
            Rule::unique('users', 'username')
                ->ignore($this->user)
                ->withoutTrashed(),
        ],
    ];
}
```

## Custom Deleted Column

If your soft-delete column uses a custom name (instead of `deleted_at`), pass it to `withoutTrashed('deleted_at_column')`.

## Summary

- `->ignore($model)` prevents self-collision errors during model update requests.
- `->withoutTrashed()` ensures soft-deleted rows do not block new or updated registrations.
- Fluently chains with additional query constraints via `->where(...)`.
