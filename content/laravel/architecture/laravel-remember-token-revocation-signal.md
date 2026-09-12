---
category: "Laravel"
tags: ["Laravel", "Authentication", "Security", "Architecture"]
date: "2026-09-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Use Laravel's remember_token as a Revocation Signal

> Use Laravel's remember_token as an invalidation signal for saved account-switching state without maintaining a dedicated token or revocation table.

When building a multi-account switcher, the application needs a way to verify whether a previously saved account entry is still valid before switching into it.

Before adding another token or revocation table to your database, consider whether Laravel already maintains state that can serve as the invalidation signal you need.

Laravel's `remember_token` on the `users` table already rotates whenever a user logs out, resets their password, or updates their credentials. You can bind saved account entries to this existing token using an HMAC.

---

## Verifying Saved Accounts with remember_token

Store a signature derived from the user ID, current `remember_token`, and the application key, rather than storing the raw token:

```php
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// 1. Keep an account available for fast switching in session or local state
$account = [
    'id' => $user->id,

    // Tie this entry to the user's current remember_token
    'hash' => hash_hmac(
        'sha256',
        $user->id . '|' . $user->remember_token,
        config()->string('app.key'),
    ),
];

// 2. Later, before switching to the saved account...
$user = User::findOrFail($account['id']);

// Recreate the expected hash from the user's current remember_token
$expected = hash_hmac(
    'sha256',
    $user->id . '|' . $user->remember_token,
    config()->string('app.key'),
);

// If the remember_token changed, the saved account entry is no longer valid
if (! hash_equals($account['hash'], $expected)) {
    // Invalidate or remove the saved entry instead of switching
    return redirect()->route('login')->with('status', 'Session expired. Please log in again.');
}

// Valid: log into the target account
Auth::login($user);
```

---

## How It Works

- **No raw token storage**: The raw `remember_token` is never stored in the account-switching entry; only the derived HMAC hash is retained.
- **Tied to current user state**: The derived value depends on the user's active `remember_token`. When the user logs out (`Auth::logout()`), changes their password, or cycles tokens, Laravel rotates `remember_token`.
- **Automatic invalidation**: Once `remember_token` changes, recomputing the expected hash produces a different value, causing `hash_equals()` to fail and rejecting the stale switcher entry.
- **Timing-safe comparison**: `hash_equals()` prevents timing attacks when validating the stored hash against the expected value.
- **Zero extra database tables**: Reuses Laravel's built-in `users.remember_token` column instead of creating and maintaining a separate revocation table.
