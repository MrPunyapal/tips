---
category: "PHP"
tags: ["PHP", "Arrays", "Syntax"]
date: "2025-12-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Unpack Arrays with Spread Syntax in PHP 8.1

> Use array unpacking (...) inside square bracket array literals for string-keyed and indexed array merging.

PHP 8.1 expanded array unpacking to support string keys inside array literals. This replaces array_merge() calls with clean spread syntax.

```php
$defaults = ['theme' => 'dark', 'notifications' => true];
$userCustom = ['notifications' => false, 'language' => 'en'];

// Unpacks and merges arrays natively
$options = [...$defaults, ...$userCustom];
```

- Replaces verbose array_merge() calls with clean spread operator syntax
- Supports string keys as of PHP 8.1 (later values overwrite earlier keys)
- Cleaner syntax for array composition and middleware pipelines
