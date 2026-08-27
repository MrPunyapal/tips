---
category: "Laravel"
tags: ["Laravel", "Testing", "Factories", "Clean Code"]
date: "2024-11-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Define Clean Model Variations with Factory State Methods

> Define expressive state modifier methods on Eloquent Factories to generate realistic model variations in test suites.

When writing unit and feature tests, manually overriding model attributes with `User::factory()->create(['is_admin' => true, 'email_verified_at' => null, 'status' => 'suspended'])` clutters test setup.

Defining dedicated state methods inside the factory creates readable, reusable model configurations.

## Defining State Methods in Factories

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role'              => 'member',
            'is_active'         => true,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

## Using States in Tests

```php
test('admin users can access system settings', function () {
    // Highly readable factory setup
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin/settings')
        ->assertOk();
});
```

## Summary

- Replaces magic array overrides with type-hinted, self-documenting methods.
- Chained fluently (`User::factory()->admin()->unverified()->create()`).
- Keeps test setups clean, consistent, and refactor-friendly.
