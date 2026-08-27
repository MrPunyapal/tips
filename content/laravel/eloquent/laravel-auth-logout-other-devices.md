---
category: "Laravel"
tags: ["Laravel", "Authentication", "Security", "Sessions"]
date: "2024-06-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Invalidate Sessions on Other Devices with Auth::logoutOtherDevices()

> Use Auth::logoutOtherDevices() to terminate a user's active login sessions across other browsers and mobile devices after password updates.

When a user updates their password or suspects their account was compromised, existing active sessions on other browsers remain valid until they expire.

Laravel provides `Auth::logoutOtherDevices()` to invalidate all sessions other than the user's current device.

## Implementing in Password Update Controllers

```php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

public function updatePassword(UpdatePasswordRequest $request)
{
    $user = $request->user();

    $user->update([
        'password' => Hash::make($request->input('password')),
    ]);

    // Invalidates all other session records in database/redis
    Auth::logoutOtherDevices($request->input('current_password'));

    return back()->with('status', 'Password updated and other devices logged out.');
}
```

## Required Middleware

Ensure `Illuminate\Session\Middleware\AuthenticateSession` is enabled in your `web` middleware stack:

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        IlluminateSessionMiddlewareAuthenticateSession::class,
    ]);
})
```

## Summary

- Invalidates session hashes stored on remote devices.
- Requires the user's plain-text current password to confirm authorization.
- Essential security practice for profile security and credential rotation.
