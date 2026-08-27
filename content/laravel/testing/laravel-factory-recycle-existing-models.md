---
category: "Laravel"
tags: ["Laravel", "Testing", "Factories", "Performance"]
date: "2023-09-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Reuse Model Instances Across Nested Factories with Factory recycle()

> Use Factory::recycle() to pass existing parent models to child factories, avoiding duplicate database record creation in test fixtures.

When generating complex test hierarchies (such as creating 10 Posts, each belonging to the same User, or multiple Orders for an existing Customer), child factories create duplicate parent models by default.

`recycle()` instructs factories to reuse existing model instances rather than creating new ones.

## Reusing Existing Models

```php
use App\Models\Post;
use App\Models\User;

$user = User::factory()->create();

// All 5 posts will be assigned to the existing $user instance
$posts = Post::factory()
    ->count(5)
    ->recycle($user)
    ->create();
```

## Recycling Collections of Models

Pass a collection of models to distribute them across child instances:

```php
$users = User::factory()->count(3)->create();

// 20 posts randomly distributed among the 3 existing users
$posts = Post::factory()
    ->count(20)
    ->recycle($users)
    ->create();
```

## Summary

- Prevents duplicate parent records from cluttering test databases.
- Speeds up test execution times by reducing total database insertions.
- Accepts single model instances or model collections.
