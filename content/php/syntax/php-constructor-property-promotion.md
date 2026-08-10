---
category: "PHP"
tags: ["PHP", "OOP", "Syntax"]
date: "2025-12-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Syntax"
---

# Simplify Class Instantiation with Constructor Property Promotion

> Combine property declarations and constructor parameter assignments in PHP 8 for concise class definitions.

Declaring class properties, parameter signatures, and manual $this->prop = $prop assignments creates boilerplate code. Constructor property promotion combines all three steps in the parameter list.

```php
namespace App\Services;

class PaymentProcessor
{
    // Property promotion combines declaration, type, and assignment
    public function __construct(
        public readonly StripeClient $client,
        private string $apiKey,
        protected int $timeout = 30,
    ) {}
}
```

- Eliminates repetitive property definitions and assignment statements
- Supports visibility modifiers (public, protected, private) and readonly flags
- Fully compatible with docblocks, attributes, and default values
