---
category: "Laravel"
tags: ["Laravel","Rector","Tooling"]
date: "2024-03-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Automate Laravel 11 casts() Method Upgrade with Rector

> Use Rector to automatically refactor legacy protected $casts array properties into the modern casts() method across your entire Laravel codebase.

Laravel 11 introduced the `casts()` method on Eloquent models, allowing fluent cast definitions, class references, and method calls inside model classes.

Rector automates converting legacy `protected $casts` properties to the new method:

```diff
1) app/Models/Post.php

- protected $casts = [
-     'tags' => 'array',
-     'published_at' => 'datetime',
-     'is_featured' => FeaturedStatus::class,
- ];

+ protected function casts(): array
+ {
+     return [
+         'tags' => 'array',
+         'published_at' => 'datetime',
+         'is_featured' => FeaturedStatus::class,
+     ];
+ }
```

- Runs across hundreds of models in seconds during framework upgrades
- Enables calling static methods directly inside cast definitions (e.g. `AsEnumCollection::of(...)`)
- Eliminates typos in array property names
