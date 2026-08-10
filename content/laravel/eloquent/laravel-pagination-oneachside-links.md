---
category: "Laravel"
tags: ["Laravel", "Pagination", "UI"]
date: "2023-06-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Control Pagination Link Density with onEachSide()

> Use onEachSide() on paginated Eloquent queries to adjust the number of page links shown beside the current page.

Default pagination controls can show too many numeric page links on mobile viewports. Calling onEachSide(1) limits pagination controls to a clean, minimal set of links.

```php
use App\Models\User;

// Displays 1 page link on each side of the active page
$users = User::paginate(15)->onEachSide(1);
```

- Controls how many numeric page buttons display beside active page number
- Prevents pagination controls from overflowing narrow mobile viewports
- Works out of the box with default Blade pagination templates
