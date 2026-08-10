---
category: "PHP"
tags: ["PHP", "Exceptions", "Best Practices"]
date: "2026-08-06"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Basics"
---

# Never Return Statements inside Finally Blocks in PHP

> Placing a return statement inside a try-catch finally block silently overrides exceptions and previous return statements.

A return statement inside a finally block executes regardless of whether an exception was thrown or caught. It silently discards pending exceptions and overwrites return values from try or catch blocks.

```php
// What does guess() return? It returns 'finally'!
function guess(): string
{
    try {
        throw new Exception('Something went wrong');
    } catch (Exception $e) {
        return 'catch';
    } finally {
        return 'finally'; // Silently overrides the catch return!
    }
}

// GOOD: Use finally strictly for resource cleanup
function processOrderClean(): bool
{
    try {
        return true;
    } finally {
        $this->cleanupLocks();
    }
}
```

- Return inside finally discards thrown exceptions without logging them
- Overwrites return values calculated in try or catch blocks
- Use finally strictly for resource cleanup like closing file handles or releasing locks
