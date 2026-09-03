---
category: "Laravel"
tags: ["Laravel", "Logging", "Queue", "Architecture"]
date: "2023-12-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Correlate Requests and Queue Jobs with the Context Facade

> Use the Context facade to capture request metadata that automatically propagates to logs, exception reports, and queued jobs.

When an HTTP request dispatches asynchronous queue jobs, contextual details (like the requesting user ID, tenant ID, or trace UUID) are traditionally lost inside background workers.

Laravel's `Context` facade stores cross-cutting metadata that automatically flows into logs and queued jobs.

## Capturing Context in Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

class CaptureRequestContext
{
    public function handle(Request $request, Closure $next)
    {
        // Add metadata to active Context
        Context::add('trace_id', (string) Str::uuid());
        Context::add('user_id', $request->user()?->id);
        Context::add('ip', $request->ip());

        return $next($request);
    }
}
```

## Automatic Propagation to Queued Jobs and Logs

When jobs are dispatched during this request, their execution in worker processes automatically retains the context:

```php
// Inside a background job:
Log::info('Generating PDF export.');

// Log entry automatically contains:
// {"trace_id":"8a9f...", "user_id":42, "ip":"127.0.0.1"}
```

## Retrieving and Checking Context Values

```php
$traceId = Context::get('trace_id');
$hasUser = Context::has('user_id');
```

## Summary

- Propagates metadata across HTTP requests, logging engines, and queued jobs.
- Enables end-to-end distributed transaction tracing.
- Native to modern Laravel with zero configuration overhead.
