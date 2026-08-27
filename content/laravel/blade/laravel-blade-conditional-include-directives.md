---
category: "Laravel"
tags: ["Laravel", "Blade", "Templates", "Clean Code"]
date: "2024-02-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Render Blade Partials Conditionally with @includeIf, @includeWhen, and @includeFirst

> Use includeIf, includeWhen, and includeFirst to cleanly handle optional partials, condition-based views, and fallback templates in Blade.

Including Blade partials with standard `@if` checks or rendering dynamic theme views that may not exist can lead to `ViewNotFoundException` errors.

Laravel provides dedicated directives to handle conditional and fallback template inclusion.

## @includeIf: Include Only If the View Exists

```blade
{{-- Renders 'widgets.custom-banner' if the file exists; does nothing otherwise --}}
@includeIf('widgets.custom-banner', ['banner' => $banner])
```

## @includeWhen / @includeUnless: Condition-Based Inclusion

```blade
{{-- Includes the admin toolbar only when the authenticated user is an admin --}}
@includeWhen(auth()->user()?->is_admin, 'partials.admin-toolbar')

{{-- Includes warning only unless user has confirmed email --}}
@includeUnless(auth()->user()?->hasVerifiedEmail(), 'partials.verify-email-notice')
```

## @includeFirst: Select the First Available Template from an Array

Ideal for custom user themes, tenant branding, or mobile fallback layouts:

```blade
{{-- Renders the user's custom theme if found, falling back to default --}}
@includeFirst([
    'themes.' . $currentTheme . '.header',
    'themes.default.header',
    'partials.header'
])
```

## Summary

- `@includeIf('view')` avoids view-missing exceptions for dynamic widgets.
- `@includeWhen($bool, 'view')` simplifies conditional view logic without wrapping in full `@if ... @endif` blocks.
- `@includeFirst([...])` finds and renders the first existing view from a fallback list.
