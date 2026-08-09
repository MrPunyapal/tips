---
category: "PHP"
tags: ["PHP", "Type Safety", "Best Practices"]
date: "2024-05-26"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Enforce Strict Typing Across PHP Classes and Methods

> Declare declare(strict_types=1); at the top of PHP files to prevent unexpected scalar type coercions.

By default, PHP coerces types scalar values (e.g. string '1' to int 1). Adding declare(strict_types=1); enforces strict type constraints on function arguments and return values.

```php
<?php

declare(strict_types=1);

namespace App\Services;

class TaxCalculator
{
    public function calculate(int $amount, float $rate): float
    {
        return $amount * $rate;
    }
}
```

- Prevents silent scalar type coercions (like string to integer conversion)
- Must be declared at the absolute top of PHP files before code execution
- Helps static analysis tools like PHPStan catch type bugs early
