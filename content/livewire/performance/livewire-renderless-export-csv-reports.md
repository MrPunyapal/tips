---
category: "Livewire"
tags: ["Laravel", "Livewire", "HTTP", "Streaming", "CSV", "Performance"]
date: "2023-11-06"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Performance"
---

# Stream CSV Report Downloads with Renderless Livewire Component Actions

> Use Livewire 3's #[Renderless] attribute to stream file downloads directly from component actions without triggering unnecessary view re-renders or database queries.

When triggering a file download or CSV export from a Livewire component, Livewire's default lifecycle runs the component's `render()` method after executing the action.

If your `render()` method executes complex database queries or chart calculations, running `render()` during a binary file download wastes server CPU and memory since the HTML response is discarded in favor of the download stream.

Livewire 3 provides the `#[Renderless]` attribute to execute an action without invoking `render()`.

## The Livewire Component

```php
namespace App\Livewire;

use App\Models\Report;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionReports extends Component
{
    public string $startDate = '';
    public string $endDate = '';
    public array $selectedColumns = ['id', 'reference', 'amount', 'created_at'];

    #[Renderless]
    public function exportCsv(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $output = fopen('php://output', 'w');

            // Write CSV header
            fputcsv($output, $this->selectedColumns);

            // Stream records in chunks
            Report::query()
                ->when($this->startDate, fn ($q) => $q->where('created_at', '>=', $this->startDate))
                ->when($this->endDate, fn ($q) => $q->where('created_at', '<=', $this->endDate))
                ->select($this->selectedColumns)
                ->chunk(500, function ($rows) use ($output): void {
                    foreach ($rows as $row) {
                        fputcsv($output, $row->toArray());
                    }
                });

            fclose($output);
        }, 'reports.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        // Heavy query executed ONLY when rendering HTML UI, NOT during CSV export
        return view('livewire.transaction-reports', [
            'reports' => Report::latest()->paginate(25),
        ]);
    }
}
```

## In the Blade Template

```blade
<div>
    <button wire:click="exportCsv" class="btn btn-secondary">
        Export CSV
    </button>
</div>
```

## Summary

- Apply `#[Renderless]` (or `$this->skipRender()` in Livewire 2/3) to file export methods in Livewire components.
- Skips the `render()` lifecycle entirely, preventing redundant database queries and HTML rendering during downloads.
- Combines with `response()->streamDownload()` for memory-efficient exports directly to the browser.
