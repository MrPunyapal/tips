---
category: "Laravel"
tags: ["Laravel", "Testing", "Factories", "Relationships"]
date: "2023-03-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Seed Eloquent Relationships Fluently with Factory has() and for()

> Use has() and for() on Eloquent Factories to create parent and child relationship hierarchies in a single readable chain.

Creating relational test models (such as a User with 3 Posts, each with 2 Comments) manually requires creating parent models and passing foreign keys to child factories.

Laravel factories provide `has()` and `for()` relationship helper methods.

## Creating Children with has()

```php
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

// Creates a User with 3 related Posts
$user = User::factory()
    ->has(Post::factory()->count(3))
    ->create();
```

## Magic Relationship Methods

Laravel provides magic `has{Relation}()` methods:

```php
// Same as has(Post::factory()->count(3))
$user = User::factory()
    ->hasPosts(3, ['is_published' => true])
    ->create();
```

## Setting Parent Relationships with for()

When generating child records that belong to an existing parent:

```php
$company = Company::factory()->create(['name' => 'Acme Corp']);

// Creates 5 users belonging to the specified company
$users = User::factory()
    ->count(5)
    ->for($company)
    ->create();
```

## Summary

- Eliminates manual foreign key assignment in test suites and database seeders.
- `has()` attaches child models (`hasMany`, `hasOne`, `morphMany`).
- `for()` attaches parent models (`belongsTo`, `morphTo`).
