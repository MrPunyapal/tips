---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "CRUD"]
date: "2022-12-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Update or Create Models Cleanly with updateOrCreate() and firstOrCreate()

> Separate lookup conditions from update attributes when upserting individual Eloquent models.

Instead of writing manual `if ($model = Model::find(...)) { $model->update(...); } else { Model::create(...); }` logic, Eloquent provides `firstOrCreate()` and `updateOrCreate()`.

Both methods accept two arrays:
1. **Lookup Attributes**: Columns used in the `WHERE` query to locate an existing record.
2. **Values**: Attributes to set or update when creating or modifying the model.

## Finding or Creating Records with firstOrCreate()

If a matching record exists, it is returned untouched. If no record exists, it is created using the merged attributes:

```php
use App\Models\User;

// Looks for a user by email. If not found, creates one with the given name and status.
$user = User::firstOrCreate(
    ['email' => 'punyapal@example.com'], // Lookup conditions
    ['name' => 'Punyapal Shah', 'status' => 'active'] // Additional attributes on create only
);
```

## Upserting Records with updateOrCreate()

If a matching record exists, it is updated with the second array. If not found, a new record is created with all attributes:

```php
use App\Models\Subscription;

$subscription = Subscription::updateOrCreate(
    ['user_id' => $user->id, 'plan_id' => 'pro_monthly'], // Lookup conditions
    ['expires_at' => now()->addMonth(), 'is_active' => true] // Attributes to update or create
);
```

## Inspecting Model Creation State

Check whether the returned model was newly created or already existed using `wasRecentlyCreated`:

```php
if ($subscription->wasRecentlyCreated) {
    logger()->info('New subscription created.');
} else {
    logger()->info('Existing subscription updated.');
}
```

## Summary

- First argument defines the `WHERE` constraints.
- Second argument specifies the values to set or update.
- Use `$model->wasRecentlyCreated` to run event triggers only on newly created records.
