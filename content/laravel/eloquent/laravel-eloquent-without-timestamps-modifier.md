---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Clean Code"]
date: "2025-05-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Update Model Attributes Without Mutating updated_at with withoutTimestamps()

> Use Model::withoutTimestamps() to update records during data migrations or internal status increments without altering their updated_at timestamps.

When running background data fixes, backfilling legacy columns, or incrementing internal counters (like view counts or sync hashes), updating a model modifies `updated_at`, which corrupts user-facing "Last Modified" dates.

The `withoutTimestamps()` method temporarily suspends automatic timestamp mutation.

## Updating Models Without Timestamp Changes

```php
use App\Models\Post;

// Updates the post without changing its updated_at column
Post::withoutTimestamps(function () use ($post, $migratedData) {
    $post->update([
        'legacy_sync_code' => $migratedData['code'],
        'is_indexed'       => true,
    ]);
});
```

## On Individual Model Instances

```php
$user->withoutTimestamps(function () use ($user) {
    $user->update(['login_count' => $user->login_count + 1]);
});
```

## Summary

- Preserves original `updated_at` timestamps during maintenance updates.
- Scoped strictly to the executed closure callback.
- Eliminates manual `$model->timestamps = false` state toggling.
