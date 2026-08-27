---
category: "Laravel"
tags: ["Laravel", "Clean Code", "Design Patterns", "Utilities"]
date: "2023-02-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Perform Fluent Mutations and Side Effects with the tap() Helper

> Use Laravel's tap() helper to execute closures on objects and return the original object without intermediate variable declarations.

When creating, mutating, or configuring objects, you often need to perform a side effect (such as saving a relation, logging a state change, or updating a property) and then return that same object.

The `tap()` helper executes the side effect and returns the value.

## Basic Usage with Closures

```php
use App\Models\User;

// Before: Needs intermediate variable
$user = User::create($attributes);
$user->assignRole('editor');
return $user;

// After: Clean one-liner using tap()
return tap(User::create($attributes), function (User $user) {
    $user->assignRole('editor');
});
```

## Higher-Order Tap Proxy

When calling a single method on an object without needing a closure:

```php
// Calls update() on $invoice and returns $invoice
return tap($invoice)->update(['is_processed' => true]);
```

## In Controller Responses

```php
public function store(StorePostRequest $request)
{
    return tap(Post::create($request->validated()), function (Post $post) {
        $post->tags()->sync($this->tags);
        logger()->info("Post created: #{$post->id}");
    });
}
```

## Summary

- Executes side effects on an object and returns the object instance.
- Eliminates temporary assignment variables in factories, controllers, and services.
- Supports both closure callbacks and higher-order method chaining.
