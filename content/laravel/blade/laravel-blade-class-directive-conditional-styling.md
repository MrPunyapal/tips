---
category: "Laravel"
tags: ["Laravel", "Blade", "CSS", "Tailwind CSS"]
date: "2023-09-06"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Toggle Conditional CSS Classes Cleanly with the @class Directive

> Use Laravel's @class Blade directive to conditionally apply CSS classes without messy inline ternary operators.

Applying conditional CSS classes (especially when using utility frameworks like Tailwind CSS) often leads to long, unreadable ternary expressions inside `class="..."` attributes.

Laravel's `@class` directive accepts an array of classes and conditional booleans.

## Using @class with Tailwind CSS

```blade
@php
    $isActive = true;
    $hasError = false;
@endphp

<button @class([
    // Base static classes (always applied)
    'px-4 py-2 font-medium rounded-lg transition-colors',

    // Conditional classes applied only if expression evaluates to true
    'bg-blue-600 text-white hover:bg-blue-700' => $isActive,
    'bg-gray-100 text-gray-700 hover:bg-gray-200' => ! $isActive,
    'border-2 border-red-500 ring-2 ring-red-200' => $hasError,
])>
    Save Changes
</button>
```

## Combining with Blade Components

```blade
{{-- In a Button component --}}
@props(['variant' => 'primary', 'disabled' => false])

<button {{ $attributes->class([
    'px-4 py-2 font-semibold rounded-md',
    'bg-indigo-600 text-white' => $variant === 'primary',
    'bg-white text-gray-900 border' => $variant === 'secondary',
    'opacity-50 cursor-not-allowed' => $disabled,
]) }}>
    {{ $slot }}
</button>
```

## Summary

- Eliminates fragile `{{ $isActive ? 'class-a' : 'class-b' }}` ternary strings.
- Automatically removes falsy keys and cleans up extra whitespace.
- Works directly on HTML elements (`@class`) and component attributes (`$attributes->class()`).
