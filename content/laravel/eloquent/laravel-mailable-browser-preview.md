---
category: "Laravel"
tags: ["Laravel", "Mail", "DX"]
date: "2023-09-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Preview Mailables Instantly in Browser Routes

> Return Mailable instances directly from route callbacks to preview compiled HTML emails in your browser without sending test emails.

Testing email layouts by sending real test emails to inboxes slows down design iterations. Returning a Mailable object directly from a route renders the HTML output live in your browser.

```php
use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/mailable-preview', function () {
    $order = Order::first();
    return new OrderShipped($order); // Renders compiled HTML email directly in browser
});
```

- Renders compiled Blade HTML email template live in browser
- Speeds up email design and responsive layout iterations
- No external mail server or SMTP trap configuration needed
