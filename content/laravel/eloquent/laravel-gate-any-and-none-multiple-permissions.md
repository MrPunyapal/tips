---
category: "Laravel"
tags: ["Laravel", "Authorization", "Gates", "Security"]
date: "2023-05-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Check Multiple Permissions at Once with Gate::any() and Gate::none()

> Use Gate::any(), Gate::all(), and Gate::none() to evaluate multiple authorization abilities in a single expressive call.

When granting access to an administrative panel or editing dashboard, checking if a user has at least one of multiple permissions (such as being an editor, moderator, or admin) usually requires chaining multiple `Gate::allows()` calls with boolean `||` operators.

Laravel provides multi-ability Gate helpers.

## Checking If Any Permission Passes with Gate::any()

```php
use Illuminate\Support\Facades\Gate;

// True if user has AT LEAST ONE of these abilities
if (Gate::any(['manage-users', 'manage-roles', 'view-audit-logs'])) {
    // Show administration panel
}
```

## Checking If All Permissions Pass with Gate::all()

```php
// True ONLY IF user possesses ALL listed abilities
if (Gate::all(['edit-invoice', 'approve-invoice', 'issue-refund'])) {
    // User can process final payment settlement
}
```

## Checking If No Permissions Pass with Gate::none()

```php
// True if user possesses NONE of these abilities
if (Gate::none(['is-banned', 'is-suspended'])) {
    // User is in good standing
}
```

## In Blade Templates

```blade
@canany(['edit-post', 'delete-post'], $post)
    <div class="post-actions">
        {{-- Show action toolbar --}}
    </div>
@endcanany
```

## Summary

- Replaces verbose `Gate::allows('a') || Gate::allows('b')` chains.
- Blade directive `@canany` provides direct template support.
- Fully supports passing model instances for policy-based checks.
