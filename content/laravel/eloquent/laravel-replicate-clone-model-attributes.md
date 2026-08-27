---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Models"]
date: "2022-12-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Duplicate Eloquent Models Cleanly with replicate()

> Clone an existing Eloquent model instance into a new, unsaved model while resetting primary keys and timestamps.

When duplicating complex records (such as cloning an invoice, template, or draft post), copying individual attributes manually is error-prone.

The `replicate()` method creates a copy of an existing model instance, clearing primary keys and timestamp values automatically so it can be saved as a new database row.

## Basic Cloning

```php
use App\Models\Invoice;

$invoice = Invoice::find(1);

// Clones attributes into a new unsaved instance
$newInvoice = $invoice->replicate();
$newInvoice->invoice_number = 'INV-2026-002';
$newInvoice->save();
```

## Excluding Specific Columns

Pass an array of attribute names to exclude them from the cloned model:

```php
use App\Models\Post;

$post = Post::find(10);

// Duplicate the post, resetting view counts and publication status
$draft = $post->replicate([
    'slug',
    'views_count',
    'is_published',
    'published_at',
]);

$draft->title = 'Copy of ' . $post->title;
$draft->save();
```

## Summary

- Clears the model primary key (`id` or custom key) and `created_at` / `updated_at` timestamps.
- Accepts an array of attribute exceptions to prevent sensitive or unique columns from copying.
- Returns a fresh, unsaved model instance ready for customization before calling `save()`.
