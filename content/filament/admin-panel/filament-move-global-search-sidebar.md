---
category: "Filament"
tags: ["Laravel", "Filament", "Livewire"]
date: "2026-07-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Admin Panel"
---

# Move Filament Global Search to Sidebar

> Customize your Filament admin panel layout by moving the global search bar to the top of the navigation sidebar.

In Filament admin panels, the global search bar renders in the top header by default. Using render hooks, you can move global search into the navigation sidebar.

Register a render hook in your service provider using `PanelsRenderHook::SIDEBAR_NAV_START`:

```php
namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): string => Blade::render('@livewire(Filament\\Livewire\\GlobalSearch::class)')
        );
    }
}
```

- Move global search out of the header and into the sidebar navigation
- `PanelsRenderHook::SIDEBAR_NAV_START` places elements right above navigation items
- Defer loading on large admin tables to keep page paint instant
