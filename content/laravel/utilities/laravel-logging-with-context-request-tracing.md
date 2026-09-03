---
category: "Laravel"
tags: ["Laravel", "Logging", "Debugging", "DevOps"]
date: "2023-04-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Attach Global Request Metadata to Logs with Log::withContext()

> Use Log::withContext() in HTTP middleware to automatically attach request IDs, user IDs, and tenant metadata to every log entry written during a request.

When debugging distributed systems or high-traffic applications, tracing which logs belong to a specific HTTP request or background job is difficult when log entries lack contextual identifiers.

`Log::withContext()` binds contextual data to the active logger instance for the remainder of the request lifecycle.

## Binding Context in Middleware

Create a request tracing middleware:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogContextMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = (string) Str::uuid();

        // Bind request metadata globally to all subsequent log calls
        Log::withContext([
            'request_id' => $requestId,
            'user_id'    => $request->user()?->id,
            'ip'         => $request->ip(),
            'url'        => $request->fullUrl(),
        ]);

        $response = $next($request);
        $response->headers->set('X-Request-ID', $requestId);

        return $response;
    }
}
```

## Automatic Context Injection

Any log call made inside controllers, jobs, or services automatically includes the context:

```php
// Inside a controller action:
Log::info('Order payment processed successfully.');

// Log output in storage/logs/laravel.log:
// [2026-02-15] local.INFO: Order payment processed successfully. {"request_id":"c7a1...", "user_id":42, "ip":"127.0.0.1"}
```

## Summary

- Injects request metadata into every log statement automatically.
- Correlates frontend errors with backend logs using `X-Request-ID`.
- Keeps individual log calls clean and focused without manual metadata arrays.
