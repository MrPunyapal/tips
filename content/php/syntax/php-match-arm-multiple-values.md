---
category: "PHP"
tags: ["PHP", "Control Flow", "Syntax"]
date: "2026-02-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Syntax"
---

# Match Multiple Values in a Single Match Arm in PHP

> Separate multiple comma-delimited values within a single match expression arm to group identical execution branches.

When multiple inputs share the exact same output branch in a match expression, list the values separated by commas in a single match arm instead of repeating arms.

```php
$status = 'processing';

$label = match ($status) {
    'pending', 'processing', 'queued' => 'In Progress',
    'completed', 'delivered'         => 'Successful',
    'failed', 'cancelled'           => 'Unsuccessful',
    default                           => 'Unknown Status',
};
```

- Groups multiple matching conditions using comma-separated expressions
- Eliminates duplicate result assignments across identical logic arms
- Evaluates using strict identity comparison (===)
