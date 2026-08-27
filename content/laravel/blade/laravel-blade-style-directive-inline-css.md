---
category: "Laravel"
tags: ["Laravel", "Blade", "CSS", "Clean Code"]
date: "2023-09-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Apply Dynamic Inline CSS with the @style Directive

> Use Laravel's @style Blade directive to conditionally compile inline style attributes without string concatenation.

When applying dynamic inline styles (such as background colors, progress bar widths, or user avatar positions), constructing inline `style="..."` strings with ternary operators creates messy HTML output.

The `@style` directive compiles an array of CSS declarations into a clean `style` attribute.

## Dynamic Progress Bars and Colors

```blade
@php
    $progress = 75;
    $isComplete = $progress >= 100;
@endphp

<div @style([
    'background-color: #3b82f6',
    'width: ' . $progress . '%',
    'font-weight: bold' => $isComplete,
    'text-transform: uppercase' => ! $isComplete,
])>
    {{ $progress }}%
</div>
```

## Rendered Output

If `$isComplete` is false, Laravel outputs:

```html
<div style="background-color: #3b82f6; width: 75%; text-transform: uppercase;">
    75%
</div>
```

## Summary

- Compiles array keys/values into valid CSS `property: value;` declarations.
- Automatically omits declarations where the boolean condition evaluates to false.
- Cleaner and less error-prone than manual inline style strings.
