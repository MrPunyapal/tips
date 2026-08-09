---
category: "Laravel"
tags: ["Laravel", "Image", "Media"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Extract Dominant Colors and Handle HEIC/AVIF Images in Laravel 13.24

> Laravel 13.24 adds dominantColor() to the Image API for extracting the primary color of an image, plus native support for HEIC and AVIF formats.

Extracting a dominant color for placeholders or UI backgrounds is now built directly into Laravel's Image API. Laravel 13.24 also adds native support for HEIC and AVIF uploads out of the box.

```php
use Illuminate\Support\Facades\Image;

$color = Image::read($path)->dominantColor();
// Returns hex string like '#8a6f4c'
```

- dominantColor() resizes to a single pixel internally and returns hex string
- Images with alpha channels return 8-digit hex (#rrggbbaa)
- HEIC/AVIF image formats are supported natively
