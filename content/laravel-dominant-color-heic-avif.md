---
category: "Laravel"
tags: ["Laravel", "Image", "Media"]
date: "2026-08-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Extract Dominant Colors and Handle HEIC/AVIF Images in Laravel 13.24

> Laravel 13.24 adds `dominantColor()` to the Image API for extracting the primary color of an image, plus native support for HEIC and AVIF formats.

Extracting a dominant color for placeholders or UI backgrounds is now built directly into Laravel's Image API. Laravel 13.24 also adds native support for HEIC and AVIF uploads out of the box.

### Extracting Dominant Color

```php
use Illuminate\Support\Facades\Image;

$color = Image::read($path)->dominantColor();
// Returns hex string like '#8a6f4c'

// Use it as a placeholder background
$placeholder = Image::read($path)
    ->contain(800, 600, background: 'dominant')
    ->toJpeg();
```

### Handling HEIC and AVIF Formats

```php
// iPhone photos (.heic) now work natively
$image = Image::read(storage_path('app/photo.heic'));
$jpeg  = $image->toJpeg();

// HEIC/AVIF validation is also supported
$request->validate([
    'avatar' => 'required|image', // Now accepts .heic, .heif, .avif
]);
```

- `dominantColor()` resizes to a single pixel internally and returns a hex string
- Images with alpha channels return 8-digit hex (`#rrggbbaa`)
- HEIC requires the Imagick extension with HEIF delegate
- AVIF works with both GD (if built against libavif) and Imagick
