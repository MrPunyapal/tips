---
category: "Laravel"
tags: ["Laravel", "Collections", "Syntax"]
date: "2023-06-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Flatten Transformed Collections with flatMap()

> Use flatMap() to map over a collection and collapse the nested array result into a flat collection in a single operation.

Mapping over a collection where callbacks return sub-arrays creates nested array structures. Combining map() and collapse() into flatMap() simplifies transformations.

```php
use App\Models\User;

// Maps over users and flattens all roles into a single flat collection
$roles = $users->flatMap(fn ($user) => $user->roles);
```

- Combines map() transformation and collapse() flattening in a single call
- Flattens nested array structures returned by mapping callbacks
- Keeps collection pipeline code concise
