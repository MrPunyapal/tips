---
category: "Laravel"
tags: ["Laravel", "Routing", "Security", "SSL"]
date: "2026-02-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Enforce HTTPS URL Generation in Production with URL::forceHttps()

> Use URL::forceHttps() in AppServiceProvider to ensure all generated URLs, assets, and pagination links use HTTPS when behind load balancers and reverse proxies.

When deploying applications behind SSL-terminating load balancers or Cloudflare proxies, internal web servers often communicate over HTTP, causing helpers like `url()`, `route()`, and pagination links to generate insecure `http://` URLs.

`URL::forceHttps()` forces all generated URLs to use the `https://` scheme.

## Configuring in AppServiceProvider

```php
namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Force HTTPS in production and staging environments
        if ($this->app->isProduction() || $this->app->environment('staging')) {
            URL::forceHttps();
        }
    }
}
```

## Summary

- Forces all generated URLs (`route()`, `asset()`, `url()`) to use `https://`.
- Resolves mixed-content security warnings on reverse proxy setups.
- Safe to conditionally disable in local development environments.
