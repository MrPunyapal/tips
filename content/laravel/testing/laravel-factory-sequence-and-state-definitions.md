---
category: "Laravel"
tags: ["Laravel", "Testing", "Factories", "Eloquent"]
date: "2023-03-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Alternate Test Attributes with Factory sequence() and States

> Use sequence() and state methods on Eloquent Factories to alternate attributes and configure complex test fixtures predictably.

When seeding test databases or generating batches of models (such as creating users with alternating roles or varying subscription tiers), writing manual loops with modulo checks is unnecessary.

Laravel factories provide the `sequence()` method to cycle through attribute sets.

## Alternating Values with sequence()

```php
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

// Creates 10 users alternating between 'admin' and 'user' roles
$users = User::factory()
    ->count(10)
    ->sequence(
        ['role' => 'admin'],
        ['role' => 'user'],
    )
    ->create();
```

## Using Closures in Sequence

```php
$users = User::factory()
    ->count(5)
    ->sequence(fn (Sequence $sequence) => [
        'email' => "user_{$sequence->index}@example.com",
        'sort_order' => $sequence->index + 1,
    ])
    ->create();
```

## Dedicated State Methods in Factories

Define expressive state methods on your Factory class:

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}

// In tests:
$completedOrder = Order::factory()->completed()->create();
```

## Summary

- Cycles sequentially through arrays of attributes for generated models.
- `$sequence->index` provides zero-indexed iteration counts.
- Factory state methods provide readable, reusable test scenario fixtures.
