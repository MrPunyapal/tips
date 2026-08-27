---
category: "Laravel"
tags: ["Laravel", "Testing", "Eloquent", "Pest PHP"]
date: "2023-03-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Verify Model Persistence with assertModelExists() and assertModelMissing()

> Replace raw assertDatabaseHas('table', ['id' => $model->id]) calls with model-aware assertModelExists() and assertModelMissing() assertions.

When writing unit and feature tests in PHPUnit or Pest PHP, checking whether an Eloquent model exists or was deleted from the database traditionally required specifying the table name and ID explicitly.

Laravel provides model-aware persistence assertions.

## Testing Model Existence

```php
use App\Models\User;

test('user can be created and deleted', function () {
    $user = User::factory()->create();

    // Model-aware existence assertion
    $this->assertModelExists($user);

    // Delete user
    $user->delete();

    // Verify model was removed from database
    $this->assertModelMissing($user);
});
```

## In Pest PHP Expectation Syntax

```php
use App\Models\Post;

it('creates post successfully', function () {
    $post = Post::factory()->create();

    expect($post)->toBeInstanceOf(Post::class);
    $this->assertModelExists($post);
});
```

## Summary

- Inspects the model's table name and primary key automatically.
- Prevents table-renaming breakage in test suites.
- Cleanly complements `assertDatabaseHas` and `assertDatabaseMissing`.
