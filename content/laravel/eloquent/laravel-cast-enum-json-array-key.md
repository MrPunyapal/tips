---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Enums", "Casts", "JSON"]
date: "2024-01-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Cast Enums Inside JSON and Array Column Keys in Eloquent

> Cast enum instances nested within JSON or array database columns using Eloquent Attribute accessors and mutators.

Laravel provides first-party enum casting for standard model columns:

```php
protected $casts = [
    'status' => PostStatus::class,
];
```

However, when an enum value is stored inside a nested JSON object or array column (such as `options->status` or `settings['status']`), Laravel's default column casting casts the entire column to an array, leaving the nested enum value as a raw string or integer.

Using Eloquent's `Attribute` accessor and mutator, you can automatically hydrate and serialize nested enum instances.

## Implementing the Nested Enum Cast

```php
namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected function options(): Attribute
    {
        return Attribute::make(
            get: function ($value): array {
                $options = json_decode($value ?? '[]', true) ?? [];

                // Hydrate string value to Backed Enum instance
                if (isset($options['status'])) {
                    $options['status'] = PostStatus::tryFrom($options['status']) ?? $options['status'];
                }

                return $options;
            },
            set: function ($value): string {
                $options = is_array($value) ? $value : (json_decode($value, true) ?? []);

                // Serialize Backed Enum instance to scalar value before storing
                if (isset($options['status']) && $options['status'] instanceof PostStatus) {
                    $options['status'] = $options['status']->value;
                }

                return json_encode($options);
            }
        );
    }
}
```

## Practical Usage

You can now interact with nested enum properties as strongly typed objects:

```php
$post = Post::find(1);

// Accessing the nested enum
if ($post->options['status'] === PostStatus::Draft) {
    // Strongly typed enum comparison
}

// Updating the nested enum
$options = $post->options;
$options['status'] = PostStatus::Published;
$post->options = $options;
$post->save();
```

## Summary

- Use Eloquent `Attribute::make()` to hydrate and serialize enums nested inside JSON attributes.
- Use `PostStatus::tryFrom()` in the getter to gracefully handle unexpected values without throwing exceptions.
- Ensures strongly typed enum consistency even for schemaless or flexible JSON payloads.
