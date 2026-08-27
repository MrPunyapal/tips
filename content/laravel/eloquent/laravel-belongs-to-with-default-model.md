---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Relationships", "Clean Code"]
date: "2022-11-09"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Prevent Null Pointer Errors with withDefault() Relationships

> Attach withDefault() to belongsTo, hasOne, and morphOne relationships to return a blank or pre-populated model instance when the relation is null.

When a relationship is optional (such as a Post having an optional Author, or an Order having an optional PromoCode), accessing attributes directly (`$post->author->name`) can throw an `Attempt to read property on null` error if the relation is unset.

Using `withDefault()` returns an empty model instance instead of `null`.

## Basic Default Model

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
```

When `$post->user_id` is `null`, `$post->author` returns a blank `User` instance where properties evaluate to `null` instead of throwing an error:

```php
// Returns null without throwing an exception
echo $post->author->name;
```

## Providing Default Attribute Values

You can pass an array of fallback values or a closure to pre-populate default attributes:

```php
public function author(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id')->withDefault([
        'name' => 'Guest Author',
        'email' => 'support@example.com',
    ]);
}
```

## Summary

- Protects views and services from `Attempt to read property on null` exceptions.
- Keeps Blade views clean by removing repetitive `$post->author?->name ?? 'Guest'` ternary operators.
- Applicable to `belongsTo`, `hasOne`, `hasOneThrough`, and `morphOne` relations.
