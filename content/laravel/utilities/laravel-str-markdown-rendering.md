---
category: "Laravel"
tags: ["Laravel", "Strings", "Markdown", "Blade"]
date: "2023-04-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Convert Markdown to Clean HTML with Str::markdown()

> Use Str::markdown() to convert GitHub Flavored Markdown into secure, sanitized HTML using CommonMark under the hood.

Rendering user comments, documentation pages, or blog post content written in Markdown traditionally required installing third-party parser packages.

Laravel includes CommonMark natively via `Str::markdown()`.

## Basic Markdown Conversion

```php
use Illuminate\Support\Str;

$markdown = "### Welcome
This is **bold** and *italic*.";

$html = Str::markdown($markdown);
// Output: "<h3>Welcome</h3>
<p>This is <strong>bold</strong> and <em>italic</em>.</p>
"
```

## Using Fluent String Syntax

```php
$html = str($post->body_markdown)->markdown();
```

## Rendering in Blade

In Blade templates, use raw output tags to display the compiled HTML:

```blade
<article class="prose">
    {!! str($post->content)->markdown([
        'html_input' => 'strip', // Strips raw unsafe HTML tags for security
        'allow_unsafe_links' => false,
    ]) !!}
</article>
```

## Summary

- Powered by the reliable `league/commonmark` parser.
- Supports security options to strip unsafe raw HTML and malicious JavaScript links.
- Available as a static method (`Str::markdown`) and fluent string helper (`str()->markdown()`).
