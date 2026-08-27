---
category: "Laravel"
tags: ["Laravel", "Routing", "HTTP", "Controllers"]
date: "2023-12-06"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Redirect to External Domains Safely with redirect()->away()

> Use redirect()->away() to send users to external third-party URLs without URL sanitization or local route resolution.

Standard `redirect($url)` in Laravel validates URLs against the application's root URL and headers. When redirecting users to external payment gateways, OAuth providers, or affiliate partners (such as Stripe Checkout or PayPal), standard redirects can sometimes conflict with security headers or internal URL parsing.

`redirect()->away()` creates a clean redirect response specifically intended for external destinations.

## Basic External Redirect

```php
use Illuminate\Http\RedirectResponse;

public function redirectToGateway(): RedirectResponse
{
    $checkoutUrl = 'https://checkout.stripe.com/c/pay/cs_live_...';

    // Generates a pure 302 redirect directly to the external domain
    return redirect()->away($checkoutUrl);
}
```

## Custom Status Codes and Headers

```php
return redirect()->away('https://partner.example.com', 301, [
    'Referrer-Policy' => 'no-referrer',
]);
```

## Summary

- Specifically designed for off-site URLs (OAuth flows, payment gateways, external documentation).
- Bypasses internal URL validation and local prefix scoping.
- Supports custom HTTP redirect status codes and headers.
