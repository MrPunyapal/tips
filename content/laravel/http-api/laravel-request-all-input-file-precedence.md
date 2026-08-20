---
category: "Laravel"
tags: ["Laravel", "HTTP", "Request", "Files"]
date: "2026-08-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Laravel 13.25 Reverses Merge Order in Request::all()

> In Laravel 13.25, Request::all() now gives submitted input precedence over uploaded files when their keys collide, while file() continues to provide direct access to uploaded files.

When handling incoming HTTP requests containing both structured form fields and uploaded files, Laravel combines input data and file uploads into a unified collection when calling `$request->all()`.

Laravel 13.25 updates the internal merge order inside `Request::all()` to ensure submitted input values take precedence whenever an input field and an uploaded file share the exact same key.

## What Changed?

Under the hood, Laravel combines the input bag (`$this->input()`) and the uploaded files bag (`$this->allFiles()`).

Prior to Laravel 13.25, the merge order placed files second:

```php
// Before Laravel 13.25
array_replace_recursive($this->input(), $this->allFiles());
```

In Laravel 13.25, this order is reversed so input is merged last:

```php
// Laravel 13.25+
array_replace_recursive($this->allFiles(), $this->input());
```

This ensures submitted input values win collisions across `$request->all()`, `$request->only()`, dynamic property access (`$request->profile`), and array access (`$request['profile']`).

## Example: Colliding Input and Upload Keys

Consider a request containing a nested `profile.name` text field alongside an uploaded file also named `profile.name`:

```php
use Illuminate\Http\Request;

$request = Request::create('/', 'POST', [
    'profile' => [
        'name' => 'Taylor',
    ],
], [], [
    'profile' => [
        'name' => $file,
        'avatar' => $avatar,
    ],
]);

// Before Laravel 13.25
// Files were merged last, so a colliding file replaced the input.
$request->all();
// profile.name => UploadedFile

// Laravel 13.25
// Input is merged last, so the submitted value takes precedence.
$request->all();
// profile.name => 'Taylor'
// profile.avatar => UploadedFile

// Need the uploaded file? file() still returns it.
$request->file('profile.name');
// UploadedFile
```

## Uploaded Files Remain Accessible via file()

This change only alters key collisions inside `$request->all()` and related helper methods. Laravel does not discard the uploaded file.

When you need the uploaded file instance, the dedicated `$request->file()` API is completely unaffected and continues to return the `UploadedFile` object directly:

```php
$avatar = $request->file('profile.name');
```

## Nested Recursive Merging

Because Laravel uses `array_replace_recursive()`, precedence only applies to specific keys that collide:
- `profile.name` exists in both input and files, so the submitted string `'Taylor'` takes precedence in `all()`.
- `profile.avatar` exists only in the files bag, so it continues to appear in `$request->all()` as an `UploadedFile` without interference.

## Summary

`Request::all()` now prefers submitted input values over files when key collisions occur, while `$request->file()` remains the dedicated way to retrieve uploaded files.
