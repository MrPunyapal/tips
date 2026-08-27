---
category: "Laravel"
tags: ["Laravel", "Mail", "Blade", "Debugging"]
date: "2025-05-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Mail"
---

# Preview Mailables Directly in Your Browser Routes

> Return Mailable instances directly from web routes to preview rendered email templates in your browser during local development.

Testing HTML email layout changes by sending real emails to Mailpit or Mailtrap after every CSS edit is slow.

Laravel mailables implement `Responsable` and `Renderable`, allowing you to return them directly from browser routes.

## Defining a Preview Route

In `routes/web.php`:

```php
use App\Mail\InvoicePaidMailable;
use App\Models\Invoice;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::get('/mailable/invoice-preview', function () {
        $invoice = Invoice::factory()->make([
            'total_amount' => 249.00,
            'status'       => 'paid',
        ]);

        // Returns rendered HTML email with inline CSS directly in your browser!
        return new InvoicePaidMailable($invoice);
    });
}
```

## Previewing Notification Mailables

For Notifications, render the mail message representation:

```php
Route::get('/notification/preview', function () {
    $user = User::factory()->make();
    return (new OrderShippedNotification($order))->toMail($user);
});
```

## Summary

- Renders responsive email layouts directly in browser tabs for fast styling iterations.
- Automatically handles inline CSS styling.
- Safe for local development without sending real SMTP traffic.
