---
category: "Laravel"
tags: ["Laravel", "Blade", "Architecture", "Configuration"]
date: "2024-02-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Share Global Template Data Across All Views with View::share()

> Use View::share() in AppServiceProvider to make global site settings, user preferences, and theme configurations available across all Blade views automatically.

Passing standard site data (such as application settings, current active categories, or company metadata) to every single controller action creates repetitive controller boilerplate.

`View::share()` injects global variables into all rendered Blade templates.

## Configuring Global Variables in AppServiceProvider

```php
namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share static and computed configuration across all views
        View::share('appName', config('app.name'));
        View::share('supportEmail', config('mail.support_address'));

        // Lazy-loaded or cached settings
        View::share('siteSettings', cache()->remember('global_settings', 3600, function () {
            return Setting::pluck('value', 'key')->toArray();
        }));
    }
}
```

## Accessing Variables in Any Blade Template

Any rendered view (including layout files and components) can access the shared variables directly:

```blade
<footer>
    <p>&copy; {{ date('Y') }} {{ $appName }}. Need help? Contact <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></p>
</footer>
```

## Summary

- Injects variables globally into all rendered views without passing them from individual controllers.
- Best paired with `cache()->remember()` when loading database models in service providers.
- Eliminates duplicated `compact('siteSettings')` calls across controllers.
