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

When building CSV export features, a common approach is to query records from the database, format them, save them to a temporary file on the server disk (`storage_path('app/temp.csv')`), and then return a download response:

```text
Traditional approach:
Query database → Save temporary CSV on disk → Send download → Delete temporary file
```

This workflow introduces unnecessary disk I/O, requires server cleanup routines to purge orphan files, and can overwhelm server memory if large record sets are loaded upfront.

Laravel allows you to stream CSV responses directly to the client as they are generated, eliminating the need for intermediate files entirely.

```text
Streamed approach:
Query database in chunks → Write CSV rows directly to php://output → Stream download
```

## A Complete Laravel Example

Using a single-action invokable controller, you can combine `response()->streamDownload()`, `php://output`, and Eloquent query chunking:

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

            // Write CSV column headers
            fputcsv($output, ['SKU', 'Name', 'Description', 'Price']);

            // Process records in chunks to control memory consumption
            Product::query()
                ->select(['sku', 'name', 'description', 'price'])
                ->chunk(500, function ($products) use ($output): void {
                    foreach ($products as $product) {
                        fputcsv($output, [
                            $product->sku,
                            $product->name,
                            $product->description,
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

Expose the controller in your routes:

```php
use App\Http\Controllers\ExportProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/products/export', ExportProductsController::class)
    ->middleware('auth');
```

## How the Components Work Together

### 1. `response()->streamDownload()`
Instead of taking a file path on disk, `streamDownload()` accepts a callback and immediately sends streaming HTTP headers (`Content-Disposition: attachment; filename="products.csv"`). Output produced inside the callback is sent directly through the HTTP response buffer to the client.

### 2. `php://output` and `fputcsv()`
Opening `fopen('php://output', 'w')` provides a writable PHP output stream. Passing this resource to PHP's built-in `fputcsv()` ensures that values containing commas, quotes, or newlines are properly escaped and written straight to the response stream without ever touching physical storage.

### 3. Query Chunking (`chunk()` and `chunkById()`)
Streaming alone does not prevent high memory usage if you load all records with `Product::all()`. 

Using `chunk(500, ...)` limits database query results to 500 hydrated models at a time, recycling memory after each batch is written to the output stream.

## `chunk()` vs `chunkById()`

- **Use `chunk()`**: For read-only queries with stable ordering or where data is not being inserted or modified concurrently during the export.
- **Use `chunkById()`**: For large datasets on active systems where new records might be added or modified while the download is streaming. `chunkById()` uses primary key pagination (`where id > last_seen_id`) instead of SQL offsets (`OFFSET 500`), preventing missed or duplicate records.

```php
Product::query()
    ->select(['id', 'sku', 'name', 'description', 'price'])
    ->chunkById(500, function ($products) use ($output): void {
        foreach ($products as $product) {
            fputcsv($output, [
                $product->sku,
                $product->name,
                $product->description,
                $product->price,
            ]);
        }
    });
```

## When to Use Native Streaming vs Export Packages

For straightforward tabular CSV exports (such as downloading order lists, user reports, or product catalogs), native PHP streams and Laravel responses provide a zero-dependency solution.

However, consider dedicated export packages (such as Laravel Excel or Spatie Simple Excel) when your requirements involve:
- Multi-sheet Excel workbooks (`.xlsx`)
- Custom cell styling, formulas, or formatting
- Generating exports asynchronously via background queue jobs with email notifications
- Complex imports and validation pipelines

## Production Considerations

- **Authorization**: Always protect export endpoints behind appropriate authorization gates or policies before streaming sensitive data.
- **Chunk Sizing**: A chunk size between 200 and 1,000 records typically provides a good balance between database roundtrips and memory efficiency.
- **Column Selection**: Use `select([...])` to retrieve only the specific database columns required for the CSV rather than hydrating full models with unused attributes.

## Summary

- Use `response()->streamDownload()` with `php://output` to send CSV data directly to the user without writing temporary files to disk.
- Combine streaming with `chunk()` or `chunkById()` to process large datasets without exhausting PHP memory limits.
- Rely on native PHP `fputcsv()` for zero-dependency escaping and formatting of simple CSV downloads.
