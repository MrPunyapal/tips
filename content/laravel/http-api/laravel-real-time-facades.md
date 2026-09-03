---
category: "Laravel"
tags: ["Laravel", "Facades", "Testing"]
date: "2025-07-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP & API"
---

# Use Real-Time Facades with the Facades Prefix

> Prefix any application class import with Facades\ to instantly treat it as a mockable Laravel Facade.

Creating explicit Facade classes for internal application services adds unnecessary boilerplate files.

Laravel's Real-Time Facades generate mockable facades on the fly simply by prefixing the namespace import with `Facades\`.

---

## 1. Controller Usage

```php
namespace App\Http\Controllers;

// Import any internal service with the Facades\ prefix
use Facades\App\Services\PaymentGateway;

class CheckoutController
{
    public function store()
    {
        // Calls methods statically with container resolution
        PaymentGateway::charge(100);
    }
}
```

---

## 2. Mocking in Tests

The main superpower of real-time facades is effortless test mocking without container bindings:

```php
use Facades\App\Services\PaymentGateway;

it('processes checkout successfully', function () {
    PaymentGateway::shouldReceive('charge')
        ->once()
        ->with(100)
        ->andReturn(true);

    $this->post('/checkout')->assertOk();
});
```

---

## Key Benefits

- **Zero Boilerplate**: No need to create a dedicated `app/Facades/PaymentGateway.php` file.
- **Instant Testability**: Call `shouldReceive()` or `spy()` directly on the imported real-time facade.
- **Container Resolution**: Laravel resolves the real underlying instance from the service container when not mocked.
