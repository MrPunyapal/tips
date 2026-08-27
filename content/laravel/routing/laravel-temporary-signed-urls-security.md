---
category: "Laravel"
tags: ["Laravel", "Routing", "Security", "URLs"]
date: "2023-09-06"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Secure Actions with Temporary Signed URLs

> Generate tamper-proof, time-limited URLs for email verification, invoice downloads, and passwordless logins with URL::temporarySignedRoute().

When creating links sent via email or external webhooks (such as one-click unsubscribe links or temporary receipt downloads), validating that the query parameters have not been tampered with is critical.

Laravel's signed routes attach a cryptographic HMAC signature (`&signature=...`) to verify URL integrity and expiration timestamps.

## Generating a Temporary Signed URL

```php
use App\Models\Invoice;
use Illuminate\Support\Facades\URL;

$invoice = Invoice::find(42);

// Creates a signed link that expires in 30 minutes
$downloadUrl = URL::temporarySignedRoute(
    'invoices.download',
    now()->addMinutes(30),
    ['invoice' => $invoice->id]
);
```

## Protecting the Route

Protect the route using the `signed` middleware:

```php
Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])
    ->name('invoices.download')
    ->middleware('signed');
```

If a user modifies the `invoice` ID in the URL or accesses the link after expiration, Laravel automatically throws an `InvalidSignatureException` (HTTP 403).

## Manual Verification in Controller

To verify the signature manually without the middleware:

```php
use Illuminate\Http\Request;

public function download(Request $request, Invoice $invoice)
{
    if (! $request->hasValidSignature()) {
        abort(403, 'This download link is invalid or has expired.');
    }

    return response()->download($invoice->pdf_path);
}
```

## Summary

- Appends cryptographic signatures and expiration timestamps to route URLs.
- Protects unauthenticated endpoints against parameter manipulation.
- `signed` middleware handles verification and rejection automatically.
