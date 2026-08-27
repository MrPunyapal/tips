---
category: "Laravel"
tags: ["Laravel", "Mail", "Performance", "Clean Code"]
date: "2023-11-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Attach In-Memory Data to Emails Without Saving Files to Disk

> Use attachData() in Laravel Mailables to send dynamically generated PDFs, CSVs, or receipts without writing temporary files to storage.

When sending emails with generated attachments (such as PDF invoices or CSV data exports), saving files to the local disk before attaching them requires managing temporary directories and scheduling cleanup jobs to prevent filling server storage.

Laravel Mailables support `attachData()` to attach raw binary strings directly from memory.

## Attaching Generated Files in a Mailable

```php
namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Invoice #' . $this->invoice->number);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.invoice-paid');
    }

    public function attachments(): array
    {
        // Generate PDF string in memory
        $pdfContent = Pdf::loadView('invoices.pdf', ['invoice' => $this->invoice])->output();

        return [
            Attachment::fromData(fn () => $pdfContent, 'invoice-' . $this->invoice->number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
```

## Using attachData in Traditional Mailable build()

```php
public function build()
{
    return $this->view('emails.invoice')
        ->attachData($this->pdfString, 'receipt.pdf', [
            'mime' => 'application/pdf',
        ]);
}
```

## Summary

- Eliminates temporary disk I/O and avoids disk space exhaustion issues.
- Supports generated PDF strings, dynamic CSV exports, and in-memory charts.
- Works cleanly with both modern `attachments()` envelope definitions and legacy `build()` methods.
