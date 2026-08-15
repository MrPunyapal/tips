---
category: "Laravel"
tags: ["Laravel", "Images", "HTTP"]
date: "2026-08-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Process Uploaded Images from Streams in Laravel 13.25

> Laravel 13.25 adds Image::fromStream(), makes toFormat() public, and pairs with toResponse() to process uploaded images and return them directly as HTTP responses.

When handling user uploads, processing an image and returning a converted preview often involves reading files from streams rather than saving intermediate files to disk. Laravel 13.25 updates the Image API with stream support, making it straightforward to load, convert, and respond in a single flow.

## Process an Uploaded Image

Here is a controller action that reads an uploaded image stream, converts it to WebP, and returns the converted image directly as an HTTP response:

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Image;

class ImagePreviewController extends Controller
{
    public function preview(Request $request): Response
    {
        // Create the image directly from the uploaded file stream.
        $image = Image::fromStream(
            $request->file('image')->readStream()
        );

        // Convert the image to WebP.
        $image->toFormat('webp');

        // Return the processed image as an HTTP response.
        return $image->toResponse();
    }
}
```

## What Each Method Does

### Image::fromStream()

Laravel 13.25 introduces `Image::fromStream()`, allowing you to instantiate an image directly from a readable stream resource such as `$request->file('image')->readStream()`:

```php
$image = Image::fromStream(
    $request->file('image')->readStream()
);
```

This reads the stream directly without requiring the uploaded file to be permanently stored on disk first.

### toFormat()

In Laravel 13.25, `toFormat()` is now public on the image instance, allowing you to convert the in-memory image representation to a target format such as WebP, PNG, or JPEG:

```php
$image->toFormat('webp');
```

Calling `toFormat()` updates the image format in memory without writing it to storage.

### toResponse()

`toResponse()` converts the processed image into an `Illuminate\Http\Response` instance with the appropriate `Content-Type` header (such as `image/webp`):

```php
return $image->toResponse();
```

## The Flow

```text
Uploaded file
    ↓
fromStream()
    ↓
toFormat('webp')
    ↓
toResponse()
```

This pattern is useful for on-the-fly preview endpoints, format conversion APIs, or serving dynamically formatted image responses directly from uploaded requests.
