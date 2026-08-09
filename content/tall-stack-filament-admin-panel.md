---
category: "Laravel"
tags: ["Laravel", "Filament", "TALL Stack"]
date: "2024-11-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Accelerate TALL Stack Development with FilamentPHP

> Use FilamentPHP to build full-featured administrative panels, forms, and tables using Livewire and Alpine.js.

Building custom admin dashboards from scratch requires writing repetitive Blade components, Livewire tables, and form validation. Filament provides pre-built TALL stack components out of the box.

```php
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')->required(),
        Select::make('role')->options([
            'admin' => 'Admin',
            'editor' => 'Editor',
        ]),
    ]);
}
```

- First-party TALL stack admin framework built on Livewire and Alpine.js
- Includes form builders, data tables, notifications, and dashboard widgets
- Extremely extensible via custom Livewire components
