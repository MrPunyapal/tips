---
category: "Laravel"
tags: ["Laravel", "Validation", "Images", "Security"]
date: "2026-03-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# Validate Image Width, Height, and Aspect Ratio with Rule::dimensions()

> Use Rule::dimensions() in validation rules to enforce exact pixel dimensions, minimum thresholds, and aspect ratios on uploaded images.

Validating avatar uploads or banner images requires ensuring images meet visual layout requirements before storing them on disk.

Laravel provides the fluent `Rule::dimensions()` helper.

## Basic Usage in Form Requests

```php
use Illuminate\Validation\Rule;

public function rules(): array
{
    return [
        // Enforce minimum and maximum pixel boundaries
        'avatar' => [
            'required',
            'image',
            Rule::dimensions()
                ->minWidth(200)
                ->minHeight(200)
                ->maxWidth(2000)
                ->maxHeight(2000),
        ],

        // Enforce exact 16:9 banner aspect ratio
        'banner' => [
            'required',
            'image',
            Rule::dimensions()
                ->minWidth(1200)
                ->ratio(16 / 9),
        ],
    ];
}
```

## Square Dimensions Shorthand

For square profile pictures, enforce a 1:1 aspect ratio:

```php
Rule::dimensions()->ratio(1 / 1)->minWidth(100);
```

## Summary

- Validates image dimensions using PHP's native image inspection before processing.
- Supports `minWidth()`, `maxWidth()`, `minHeight()`, `maxHeight()`, `width()`, `height()`, and `ratio()`.
- Prevents layout breaks caused by disproportionate uploaded media.
