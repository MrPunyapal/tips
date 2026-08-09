---
category: "Laravel"
tags: ["Laravel","HTTP","Filesystem"]
date: "2023-06-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Stream and Download Files Efficiently in Laravel Controllers

> Return proper HTTP file responses using response()->download(), response()->streamDownload(), and response()->file() for downloads and inline previews.

Serving files to users requires setting correct HTTP disposition headers and content types.

Laravel provides explicit response helpers for file handling:

```php
use Illuminate\Support\Facades\Storage;

// Download local file directly
public function downloadContract()
{
    return response()->download(storage_path('app/contract.pdf'), 'Agreement.pdf');
}

// Stream generated content without storing to disk
public function exportCsv()
{
    return response()->streamDownload(function () {
        echo "id,name\n1,John";
    }, 'users.csv');
}

// Display file inline in browser (e.g. PDF preview)
public function previewInvoice()
{
    return response()->file(storage_path('app/invoice.pdf'));
}
```

- `download()`: Triggers browser save dialog with custom filename
- `streamDownload()`: Streams dynamic output directly to browser with 0 disk storage overhead
- `file()`: Renders image or PDF inline inside browser tab
