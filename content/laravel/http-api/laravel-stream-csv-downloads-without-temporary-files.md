---
category: "Laravel"
tags: ["Laravel", "HTTP", "Streaming", "CSV", "Database"]
date: "2026-08-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Stream CSV Downloads in Laravel Without Creating Temporary Files

> Combine response()->streamDownload(), php://output, and query chunking to stream CSV exports directly to the browser without writing temporary files to disk.

Generating CSV exports often involves writing a temporary file to disk (`storage_path('app/temp.csv')`), returning a download response, and cleaning up the file afterward. This introduces unnecessary disk I/O and risk of disk space exhaustion.

Using `response()->streamDownload()`, you can write CSV rows directly to `php://output` in chunked database batches with minimal memory consumption and zero disk usage.

---

## Invokable Controller Example

```php
namespace App\Http\Controllers;

use App\Models\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportProductsController
{
    public function __invoke(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');

            // Header row
            fputcsv($output, ['SKU', 'Name', 'Price']);

            // Stream records in batches using primary-key pagination
            Product::query()
                ->select(['id', 'sku', 'name', 'price'])
                ->chunkById(500, function ($products) use ($output): void {
                    foreach ($products as $product) {
                        fputcsv($output, [
                            $product->sku,
                            $product->name,
                            $product->price,
                        ]);
                    }
                });

            fclose($output);
        }, 'products.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
```

---

## Key Technical Details

- **`php://output`**: A write-only output stream wrapper that writes directly into PHP's output buffer sent to the HTTP client.
- **`fputcsv()`**: Handles escaping of commas, quotes, and newlines natively in PHP.
- **`chunkById()`**: Paginates by `WHERE id > last_seen_id` rather than SQL `OFFSET`, preventing duplicate or skipped records if the table receives new rows during the export.
- **Selective Columns**: Always pair `chunkById()` with explicit `select(['id', ...])` to hydrate only necessary columns instead of full model instances.
