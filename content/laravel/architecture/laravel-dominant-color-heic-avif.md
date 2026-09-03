---
category: "Laravel"
tags: ["Laravel", "Image", "Media"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Extract Dominant Colors and Handle HEIC/AVIF Images in Laravel 13.24

> Laravel 13.24 adds dominantColor() to the Image API for extracting the primary color of an image, plus native support for HEIC and AVIF formats.

Extracting a dominant color for UI placeholders or background tints is built directly into Laravel's Image API. Laravel 13.24 also adds native decoding for Apple HEIC and modern AVIF images out of the box.

---

## Code Example

```php
use Illuminate\Support\Facades\Image;

// 1. Extract dominant color (returns hex string like '#8a6f4c')
$color = Image::read($path)->dominantColor();

// 2. Read and convert iPhone HEIC or AVIF images natively
$jpeg = Image::read(storage_path('app/photos/photo.heic'))
    ->toJpeg();
```

---

## Key Benefits

- **`dominantColor()`**: Resizes to a single pixel internally and returns a hex string (`#rrggbbaa` for images with alpha channels).
- **Format Flexibility**: iPhone `.heic` photos and `.avif` web formats work natively through standard `Image::read()`.
- **Driver Requirements**: HEIC decoding requires the Imagick extension with the HEIF delegate. AVIF works with GD (compiled with libavif) or Imagick.
