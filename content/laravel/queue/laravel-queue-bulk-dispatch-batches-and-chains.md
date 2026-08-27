---
category: "Laravel"
tags: ["Laravel", "Queue", "Architecture", "Performance"]
date: "2026-01-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Coordinate Complex Background Workflows with Bus::batch() and Bus::chain()

> Use Bus::batch() to run parallel background jobs with completion callbacks, and Bus::chain() to execute sequential dependencies.

When managing complex background tasks (such as importing thousands of CSV rows in parallel and sending an email when complete, or running a 3-step billing pipeline where step 2 depends on step 1), manual job chaining is complex.

Laravel provides the `Bus` facade for workflow coordination.

## Running Parallel Jobs with Bus::batch()

```php
use App\Jobs\ProcessCsvChunkJob;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

$jobs = collect($chunks)->map(fn ($chunk) => new ProcessCsvChunkJob($chunk));

$batch = Bus::batch($jobs)
    ->then(function (Batch $batch) {
        // Runs only when ALL jobs in the batch complete successfully
        logger()->info('All CSV chunks processed successfully.');
    })
    ->catch(function (Batch $batch, Throwable $e) {
        // Runs on the first failed job in the batch
        logger()->error('Batch processing encountered an error: ' . $e->getMessage());
    })
    ->finally(function (Batch $batch) {
        // Runs after all jobs have finished (success or failure)
    })
    ->name('Import Product Catalog')
    ->dispatch();
```

## Sequential Execution with Bus::chain()

```php
use App\Jobs\ChargeSubscriptionJob;
use App\Jobs\GeneratePdfInvoiceJob;
use App\Jobs\SendInvoiceEmailJob;

Bus::chain([
    new ChargeSubscriptionJob($user),
    new GeneratePdfInvoiceJob($user),
    new SendInvoiceEmailJob($user),
])->dispatch();
```

## Summary

- `Bus::batch()`: Runs multiple jobs in parallel across available queue workers with `then()`, `catch()`, and `finally()` hooks.
- `Bus::chain()`: Runs jobs sequentially, stopping immediately if any job in the chain fails.
- Essential for scalable background architectures.
