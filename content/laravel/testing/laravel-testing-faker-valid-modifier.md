---
category: "Laravel"
tags: ["Laravel", "Testing", "Faker", "Clean Code"]
date: "2024-12-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Enforce Specific Fake Data Constraints with Faker's valid() Modifier

> Use Faker's valid() modifier in seeders and factories to generate values that strictly satisfy custom validation closures.

When generating fake data for models with unique business rules (such as generating even numbers, non-empty usernames, or valid age ranges), standard Faker methods like `fake()->numberBetween(1, 100)` can produce invalid domain values.

Faker's `valid()` modifier keeps retrying generation until the value satisfies a condition callback.

## Generating Valid Fake Values

```php
// Generate an even number
$evenNumber = fake()->valid(fn ($num) => $num % 2 === 0)->numberBetween(1, 100);

// Generate a username that does not contain reserved words
$username = fake()->valid(function ($name) {
    return ! in_array(strtolower($name), ['admin', 'root', 'support']);
})->userName();
```

## In Factory Definitions

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Ensure generated discount is always in increments of 5
            'discount_percentage' => fake()->valid(fn ($n) => $n % 5 === 0)->numberBetween(5, 50),
        ];
    }
}
```

## Summary

- Filters generated values through a validator closure.
- Continues generating until a truthy validation condition is met.
- Prevents invalid test dataset generation without post-processing code.
