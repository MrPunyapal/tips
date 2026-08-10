---
category: "Laravel"
tags: ["Laravel","HTTP Client","Middleware"]
date: "2025-12-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Run Post-Request Callbacks with afterResponse() in Laravel 12.44

> Laravel 12.44 adds the afterResponse() hook to the HTTP client, allowing you to attach response logging, metrics, and error handling callbacks cleanly inside client macros.

When building reusable API integrations with HTTP client macros, logging responses or checking status codes previously required wrapping request calls in every controller or service.

With `afterResponse()`, you can attach post-request callbacks directly inside your HTTP client macro definitions:

```php
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

Http::macro('github', fn () =>
    Http::baseUrl('https://api.github.com')
        ->acceptJson()

        // Metrics & request logging
        ->afterResponse(fn (Response $response) => logger()
            ->info('GitHub API response', [
                'status' => $response->status(),
            ])
        )

        // Conditional error handling
        ->afterResponse(fn (Response $response) => $response->failed()
            && logger()->error('GitHub API call failed')
        )
);

// Clean controller usage without inline log boilerplate
Http::github()->get('/repos/laravel/framework');
```

- Callbacks execute automatically after the response is received
- Multiple `afterResponse()` callbacks can be chained on a single request or macro
- Keeps API client logic self-contained inside service providers
