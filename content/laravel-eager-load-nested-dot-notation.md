---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Relationships"]
date: "2023-06-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Eager Load Nested Relationships with Dot Notation

> Use dot-notation strings inside with() to eager load deeply nested relationships in single database query chains.

When fetching models that require multi-level relationships (e.g. Posts -> Comments -> Author), use dot-notation strings in with() to eager load all levels efficiently.

```php
use App\Models\Post;

// Eager loads post author, comments, and comment authors
$posts = Post::with(['author', 'comments.author'])->get();
```

- Eager loads multi-level nested relationships cleanly in dot-notation strings
- Prevents cascading N+1 query problems across nested views
- Allows applying constraints to nested levels using array key closures
