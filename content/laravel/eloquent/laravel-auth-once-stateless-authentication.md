---
category: "Laravel"
tags: ["Laravel", "Authentication", "Security", "API"]
date: "2023-05-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Authenticate Users for a Single Request with Auth::once()

> Use Auth::once() to authenticate a user for a single request lifecycle without creating persistent session cookies or saving session state.

When building stateless APIs, basic HTTP authentication, or CLI authentication commands, using standard `Auth::attempt()` writes session cookies to the response and stores session data on the server.

`Auth::once()` validates user credentials and logs the user in for the current request only.

## Stateless Authentication in Controllers or Middleware

```php
use Illuminate\Support\Facades\Auth;

public function handleApiRequest(Request $request)
{
    $credentials = $request->only('email', 'password');

    // Validates credentials and logs in for this single request only
    if (Auth::once($credentials)) {
        // Authenticated user is available via Auth::user()
        $user = Auth::user();
        return response()->json(['data' => $user->getProfile()]);
    }

    return response()->json(['error' => 'Invalid credentials.'], 401);
}
```

## Authenticating by Model Instance

To log in by user ID or model instance for one request:

```php
// Authenticates user #42 for current request without session cookies
Auth::onceUsingId(42);
```

## Summary

- Zero session overhead and zero cookie generation.
- Ideal for stateless microservices, webhook validation, and CLI scripts.
- Makes the authenticated user available via `Auth::user()` throughout the request.
