---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Transactions"]
date: "2026-08-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Refresh and Lock an Existing Eloquent Model with refreshForUpdate()

> Laravel 13.27 adds refreshForUpdate(), making it easier to refresh an existing Eloquent model with the latest database state while locking its row for update.

A model is often loaded before a transaction starts.

For example, route model binding may already give you a Product instance:

```php
public function purchase(Product $product): Response
{
    DB::transaction(function () use ($product) {
        // ...
    });
}
```

If you need the latest database state and a pessimistic lock before modifying the row, the traditional approach was to query the model again.

## Before Laravel 13.27

You would typically need to create another query, find the model by its primary key, and replace the existing instance:

```php
public function purchase(Product $product): Response
{
    DB::transaction(function () use ($product) {
        $product = Product::query()
            ->lockForUpdate()
            ->findOrFail($product->getKey());

        if ($product->stock === 0) {
            throw new RuntimeException('The product is out of stock.');
        }

        $product->decrement('stock');
    });

    // ...
}
```

The model was already available, but we still needed another query and had to reassign the result.

## Laravel 13.27: refreshForUpdate()

Laravel 13.27 adds a simpler way to do the same thing:

```php
public function purchase(Product $product): Response
{
    DB::transaction(function () use ($product) {
        $product->refreshForUpdate();

        if ($product->stock === 0) {
            throw new RuntimeException('The product is out of stock.');
        }

        $product->decrement('stock');
    });

    // ...
}
```

refreshForUpdate() reloads the existing model from the database while applying lockForUpdate() to the query used to refresh it.

The existing model instance is updated in place, so there is no need to query the model again and reassign it.

## refresh() vs refreshForUpdate()

The two methods are similar, but refreshForUpdate() also applies a row lock to the refresh query.

```php
$product->refresh();
```

Reloads the model with fresh attributes from the database.

```php
$product->refreshForUpdate();
```

Reloads the model with fresh attributes and applies lockForUpdate() to the query.

So when you already have an Eloquent model and need a fresh, locked version of that same instance, refreshForUpdate() expresses that intent directly.

## A practical stock example

Imagine a product has limited stock and multiple requests can attempt to purchase it at the same time.

The model may have been loaded before the transaction starts, so its current attributes may no longer represent the latest database state.

Refresh and lock the row before checking the current stock:

```php
DB::transaction(function () use ($product) {
    $product->refreshForUpdate();

    if ($product->stock < 1) {
        throw new RuntimeException('The product is out of stock.');
    }

    $product->decrement('stock');
});
```

The stock check now uses the freshly loaded database state after the row has been locked for update.

## The model stays the same instance

refreshForUpdate() refreshes the existing model instance rather than returning a different model that you need to assign.

```php
DB::transaction(function () use ($product) {
    $result = $product->refreshForUpdate();

    // $result and $product refer to the same model instance.
});
```

The model's attributes are refreshed from the database, and its dirty state is synchronized with those fresh attributes.

You can simply continue using $product after calling the method.

## Why this is useful with route model binding

A common flow looks like this:

```text
Route model binding
        ↓
Product instance already exists
        ↓
Transaction starts
        ↓
refreshForUpdate()
        ↓
Fresh database attributes + row lock
        ↓
Continue using the same Product instance
```

This is where the new method removes a small but repetitive piece of Eloquent code.

Instead of looking up the model again by its primary key, you can simply do:

```php
$product->refreshForUpdate();
```

## Keep it inside the transaction

refreshForUpdate() is intended for situations where the row needs to be locked while the transaction performs its work:

```php
DB::transaction(function () use ($product) {
    $product->refreshForUpdate();

    // Read the current state...
    // Perform the required changes...
});
```

The method does not replace the transaction itself.

The row lock is part of the database transaction, so the refresh and the work that depends on that lock should be performed within the same transaction.

## What happens under the hood?

refreshForUpdate() uses the same refresh flow as refresh(), but builds the query with lockForUpdate():

```php
$this->newQueryWithoutScopes()
    ->lockForUpdate();
```

Laravel then uses that query to reload the current model by its existing primary key.

This means the feature is not a completely different way of refreshing a model. It is the existing refresh behavior with the update lock applied to the refresh query.

## Summary

- refreshForUpdate() was added in Laravel 13.27.
- It reloads the existing Eloquent model with fresh database attributes.
- The refresh query uses lockForUpdate().
- The existing model instance is refreshed in place.
- You no longer need to query and reassign an already-loaded model just to refresh it with a row lock.
- It is particularly useful when a model was loaded before a transaction, such as through route model binding.
- Use it inside the transaction where the locked row needs to be read or modified.
- It does not eliminate the database query. It provides a cleaner API for refreshing and locking the existing model.

If you already have the Eloquent model, Laravel 13.27 gives you a cleaner way to refresh it and lock it for update.
