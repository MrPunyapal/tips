---
category: "Laravel"
tags: ["Laravel", "Localization", "i18n"]
date: "2023-06-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Routing"
---

# Simplify App Translations with JSON Translation Files

> Use JSON files in lang/ for localization to write default language strings directly as translation keys.

Managing translation key strings like messages.welcome in short PHP translation files is tedious. Using JSON files (e.g. lang/es.json) lets you use full English sentences as translation keys.

```json
// lang/es.json
{
    "Welcome to our application": "Bienvenido a nuestra aplicación",
    "Hello :name": "Hola :name"
}
```

- Uses full default language strings as keys (no abstract keys like home.title)
- Blade helper __('Welcome to our application') falls back to key if translation is missing
- Supports dynamic parameters like __('Hello :name', ['name' => 'Punyapal'])
