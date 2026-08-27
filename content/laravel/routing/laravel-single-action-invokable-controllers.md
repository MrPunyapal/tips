---
category: "Laravel"
tags: ["Laravel", "Routing", "Architecture", "Clean Code"]
date: "2024-02-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Simplify Complex Actions with Single-Action Invokable Controllers

> Use __invoke() in dedicated Single-Action Controllers to encapsulate complex domain operations into isolated, focused classes.

As controllers grow, large multi-action classes often accumulate disparate dependencies and private helper methods used by only one endpoint.

Single-action controllers implement PHP's magic `__invoke()` method, focusing on one specific responsibility.

## Generating an Invokable Controller

Generate an invokable controller using Artisan:

```bash
php artisan make:controller GenerateInvoiceReportController --invokable
```

## Controller Implementation

```php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenerateInvoiceReportController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $invoices = Invoice::whereBetween('created_at', [
            $request->date('start_date'),
            $request->date('end_date'),
        ])->get();

        return response()->view('reports.invoices', compact('invoices'));
    }
}
```

## Clean Route Registration

When registering an invokable controller in your routes file, pass the class name directly without specifying a method:

```php
use App\Http\Controllers\GenerateInvoiceReportController;
use Illuminate\Support\Facades\Route;

// Clean, single-class route mapping
Route::get('/reports/invoices', GenerateInvoiceReportController::class);
```

## Summary

- Encapsulates isolated, high-complexity operations (reports, checkouts, webhooks).
- Keeps dependency injection minimal and focused on that single action.
- Route definitions map cleanly to the class name without requiring method strings.
