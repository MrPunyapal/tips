---
category: "Laravel"
tags: ["Laravel","Blade"]
date: "2023-12-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Import Multiple Classes in Blade with Array @use Syntax

> Import multiple PHP classes or custom aliases inside Blade templates using array arguments in the @use directive.

The Blade `@use` directive allows importing PHP classes directly inside templates. Punyapal Shah contributed a Pull Request to Laravel expanding `@use` to accept array arguments.

You can import multiple classes or define custom aliases in a single directive:

```blade
{{-- Import multiple model classes --}}
@use(['App\Models\User', 'App\Models\Post'])

{{-- Import with custom aliases --}}
@use(['App\Models\User' => 'ModelUser', 'App\Models\Post' => 'ModelPost'])

{{-- Single class import still works --}}
@use('App\Models\User', 'ModelUser')
```

- Replaces repetitive `@php use ... @endphp` blocks at the top of Blade templates
- Keeps Blade template imports grouped in a clean, readable structure
- Supports alias mapping directly inside array keys and values
