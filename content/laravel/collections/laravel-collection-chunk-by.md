---
category: "Laravel"
tags: ["Laravel", "Collections", "PHP"]
date: "2026-09-04"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Collections"
---

# Group Consecutive Items with chunkBy()

> Use `chunkBy()` to split a Collection whenever the resolved value changes, without manually comparing items with the previous chunk.

Before Laravel 13.30, grouping consecutive items with the same value could require `chunkWhile()`:

```php
$products->chunkWhile(
    fn (Product $product, $key, $chunk) =>
        $product->status === $chunk->last()->status
);
```

Laravel 13.30 adds `chunkBy()` for this pattern:

```php
$products->chunkBy('status');
```

You can also provide a callback when the value needs to be calculated:

```php
$products->chunkBy(
    fn (Product $product) => $product->status
);
```

The important difference from `groupBy()` is that `chunkBy()` keeps **consecutive values together**. If the same value appears again later, it starts a new chunk:

```php
collect(['paid', 'paid', 'pending', 'pending', 'paid'])
    ->chunkBy(fn (string $status) => $status)
    ->all();

// [
//     ['paid', 'paid'],
//     ['pending', 'pending'],
//     ['paid'],
// ]
```

It also works with nested attributes:

```php
$orders->chunkBy('customer.id');
```

Use `chunkBy()` when the order of the items matters and you want to split them into consecutive groups based on a value.
