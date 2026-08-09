---
category: "Laravel"
tags: ["Laravel", "Testing", "Pest", "Enums"]
date: "2026-06-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Assert JSON Columns and Backed Enums with assertDatabaseHas

> In Laravel and Pest tests, `assertDatabaseHas()` natively queries nested JSON properties using arrow syntax and accepts backed Enum instances directly.

When testing database records containing JSON attributes or PHP backed enums, there is no need to manually cast enums to strings or encode JSON blobs.

Laravel's `assertDatabaseHas()` natively supports arrow syntax `->` for nested JSON keys and serializes backed enums automatically.

### Example in Pest PHP:

```php
use App\Enums\UserRole;
use App\Enums\SubscriptionStatus;

it('stores user preferences and subscription role correctly', function () {
    $this->postJson('/api/register', [
        'name' => 'Punyapal Shah',
        'role' => UserRole::Maintainer,
        'settings' => [
            'theme' => 'dark',
            'notifications' => ['email' => true],
        ],
    ])->assertCreated();

    // Query JSON attributes & Enums directly:
    $this->assertDatabaseHas('users', [
        'name' => 'Punyapal Shah',
        'role' => UserRole::Maintainer, // Enum backed value handled automatically
        'settings->theme' => 'dark',     // Query nested JSON property
        'settings->notifications->email' => true,
    ]);
});
```
