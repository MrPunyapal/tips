---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Performance", "Clean Code"]
date: "2023-11-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Efficient Unique Slug Generation in Eloquent Without Looping Queries

> Generate unique database slugs in Eloquent by querying the maximum existing numerical suffix directly instead of executing repeated queries in a while loop.

When generating URL slugs for blog posts, products, or articles, collisions occur when two records share the same title (`"My First Post"`).

A frequent implementation uses a `while` loop that repeatedly queries the database (`post-1`, `post-2`, `post-3`, ...) until an available slug is found. On busy applications with many duplicate titles, this pattern generates dozens of sequential database roundtrips for a single record creation.

You can determine the next unique slug in a single query by inspecting the maximum numeric suffix using SQL functions.

## The Efficient Implementation

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Article $article): void {
            if (empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $baseSlug = str($title)->slug()->value();

        // Check if base slug is already available
        if (static::where('slug', $baseSlug)->doesntExist()) {
            return $baseSlug;
        }

        // Query the maximum existing numeric suffix in a single query
        $maxSuffix = static::query()
            ->where('slug', 'LIKE', "{$baseSlug}-%")
            ->max(DB::raw('CAST(SUBSTRING_INDEX(slug, "-", -1) AS SIGNED)'));

        if ($maxSuffix === null || $maxSuffix <= 0) {
            return "{$baseSlug}-2";
        }

        return "{$baseSlug}-" . ($maxSuffix + 1);
    }
}
```

## How It Works

1. **Initial Availability Check**: Checks if the clean base slug (`"laravel-tips"`) exists. If available, it returns immediately with 1 simple indexed lookup.
2. **`SUBSTRING_INDEX` Extraction**: For duplicate titles, MySQL's `SUBSTRING_INDEX(slug, "-", -1)` extracts the trailing characters after the last hyphen.
3. **`CAST(... AS SIGNED)`**: Converts the extracted substring into an integer so the `MAX()` aggregate computes the numerical maximum rather than alphabetical sorting.
4. **Calculated Next Suffix**: Increments the maximum found suffix (`max + 1`), guaranteeing uniqueness without sequential trial-and-error queries.

## Performance Comparison

- **While Loop Approach**: Runs (N) database queries (where (N) is the number of existing collisions).
- **Direct Max Calculation**: Always runs exactly 2 queries regardless of whether 2 or 2,000 colliding records exist.

## Summary

- Avoid `while (Model::whereSlug(...)->exists())` loops that create unpredictable N+1 database roundtrips.
- Use SQL string extraction and integer casting to retrieve the maximum numerical suffix in a single lookup.
- Guarantees fast, predictable execution time during bulk imports and high-traffic record creation.
