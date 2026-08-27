---
category: "Laravel"
tags: ["Laravel", "HTTP", "API", "Authentication"]
date: "2024-05-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Authenticate External API Requests Cleanly with withToken()

> Use withToken() on the Laravel HTTP client to attach Authorization Bearer tokens without manual header string formatting.

When sending authenticated requests to third-party APIs, manually setting `['headers' => ['Authorization' => 'Bearer ' . $token]]` is repetitive.

The Laravel HTTP client provides the `withToken()` method.

## Basic Usage

```php
use Illuminate\Support\Facades\Http;

// Attaches 'Authorization: Bearer <token>' header automatically
$response = Http::withToken($apiToken)
    ->get('https://api.github.com/user/repos');
```

## Custom Token Types

If an API uses a custom authentication scheme (such as `Basic` or `Token` instead of `Bearer`), pass the prefix as the second argument:

```php
// Attaches 'Authorization: Token <key>'
$response = Http::withToken($accessKey, 'Token')
    ->post('https://api.custom-service.com/events', $payload);
```

## Summary

- Shorthand for attaching `Authorization` headers.
- Defaults to the standard `Bearer` token prefix.
- Cleanly replaces manual header array concatenation.
