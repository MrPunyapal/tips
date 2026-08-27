---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Models", "Clean Code"]
date: "2023-02-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Compare Eloquent Models with is() and isNot()

> Compare two Eloquent model instances using is() and isNot() to verify matching primary keys, table names, and database connections.

Comparing model identity using equality operators (`$user1 == $user2`) compares all model attributes, which fails if one model has dirty attributes or loaded relationships. Comparing raw IDs (`$user1->id === $user2->id`) fails if one of the variables is `null` or a different model type.

The `is()` and `isNot()` methods verify whether two instances represent the exact same database record.

## Comparing Models

```php
use App\Models\User;

$currentUser = auth()->user();
$postAuthor = $post->author;

// Manual ID comparison (risks null pointer if postAuthor is null)
if ($postAuthor && $currentUser->id === $postAuthor->id) {
    // ...
}

// Clean Eloquent model comparison (safe against null)
if ($currentUser->is($postAuthor)) {
    // Both represent the same user on the same database connection
}
```

## Negated Comparison with isNot()

```php
if ($currentUser->isNot($postAuthor)) {
    abort(403, 'You are not the author of this post.');
}
```

## What is() Verifies

1. The comparison object is not `null`.
2. Both models share the same primary key value.
3. Both models share the same database table name.
4. Both models share the same database connection name.

## Summary

- Safe from null reference exceptions when comparing optional relationships.
- Compares database identity instead of serialized in-memory attributes.
- Improves authorization checks and policy logic readability.
