---
category: "Laravel"
tags: ["Laravel", "Architecture", "HTTP"]
date: "2026-03-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Extract Complex Controller Responses into Responsable Classes

> Implement the Responsable interface to create dedicated response objects that handle headers, view data, and formatting outside controllers.

When controller actions accumulate complex redirect logic, header building, or conditional JSON rendering, move response logic into a dedicated class implementing Illuminate\Contracts\Support\Responsable.

```php
namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class InvoiceExportResponse implements Responsable
{
    public function __construct(private array $data) {}

    public function toResponse($request): JsonResponse
    {
        return response()->json($this->data)
            ->header('X-Export-Timestamp', now()->timestamp);
    }
}

// Controller Action
return new InvoiceExportResponse($data);
```

- Implements Responsable interface with toResponse($request) signature
- Keeps controller actions clean and focused strictly on request handling
- Allows returning custom response instances directly from routes or controllers
