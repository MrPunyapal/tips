---
category: "Laravel"
tags: ["Laravel", "Enums", "Architecture", "Clean Code"]
date: "2024-01-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Human-Friendly Enum Case Labels with a Reusable HasLabel Trait

> Add human-readable display labels to PHP 8.1+ enums automatically using a lightweight HasLabel trait without maintaining manual match statements.

When using backed Enums in Laravel models and Blade views, you often need a formatted, user-friendly label (such as displaying `"Partially Paid and Refunded"` for `PaymentStatus::PartiallyPaidAndRefunded`).

Maintaining manual `match ($this)` methods across dozens of application enums is repetitive. A lightweight trait can derive clean titles automatically while allowing custom overrides when needed.

## The HasLabel Trait

```php
namespace App\Traits;

trait HasLabel
{
    public function label(): string
    {
        // Convert case name (e.g. PARTIALLY_REFUNDED or PartiallyRefunded) to Title Case
        $clean = str_replace('_', ' ', $this->name);

        // Convert camelCase / PascalCase boundaries to spaced words
        $spaced = preg_replace('/(?<!^)[A-Z]/', ' $0', $clean);

        return ucwords(strtolower(trim($spaced)));
    }
}
```

## Applying the Trait to Enums

```php
namespace App\Enums;

use App\Traits\HasLabel;

enum PaymentStatus: int
{
    use HasLabel;

    case PENDING = 1;
    case FAILED = 2;
    case REFUNDED = 3;
    case PARTIALLY_REFUNDED = 4;
    case PARTIALLY_PAID = 5;
    case PAID = 6;
    case PARTIALLY_PAID_AND_REFUNDED = 7;
}
```

## Usage in Controllers and Blade Views

```php
use App\Enums\PaymentStatus;

PaymentStatus::PENDING->label();
// Output: "Pending"

PaymentStatus::PARTIALLY_REFUNDED->label();
// Output: "Partially Refunded"

PaymentStatus::PARTIALLY_PAID_AND_REFUNDED->label();
// Output: "Partially Paid And Refunded"
```

In Blade templates:

```blade
<span class="badge">
    {{ $order->payment_status->label() }}
</span>
```

## Overriding Specific Labels

If a specific case requires specialized branding or wording that differs from the automated title, override the method:

```php
enum InvoiceStatus: string
{
    use HasLabel;

    case DRAFT = 'draft';
    case SENT = 'sent';
    case OVERDUE = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::OVERDUE => 'Action Required: Overdue',
            default => $this->defaultLabel(),
        };
    }
}
```

## Summary

- Use a `HasLabel` trait to generate consistent, human-friendly titles from enum case names automatically.
- Eliminates boilerplate `match` expressions across project enums.
- Easily customizable for specific localized or marketing terminology when needed.
