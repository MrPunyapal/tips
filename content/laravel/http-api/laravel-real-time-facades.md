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

Creating explicit Facade classes for internal services adds boilerplate. Laravel's Real-Time Facades generate mockable facades on the fly by prefixing namespace imports with Facades\.

```php
namespace App\Http\Controllers;

// Import class with Facades\ prefix for instant facade capabilities
use Facades\App\Services\PaymentGateway;

class CheckoutController
{
    public function store()
    {
        PaymentGateway::charge(100); // Executed as real-time facade
    }
}
```

- Eliminates boilerplate dedicated Facade class files
- Allows instant test mocking via PaymentGateway::shouldReceive()
- Resolves underlying service class instance from container automatically
