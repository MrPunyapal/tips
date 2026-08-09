---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database"]
date: "2025-06-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Use $model->touch() Instead of Manual Timestamp Updates

> Use the built-in touch() method to update updated_at timestamps on Eloquent records cleanly.

Manually assigning $model->updated_at = now(); $model->save(); is repetitive. Calling $model->touch() updates timestamps and persists changes in a single line.

```php
use App\Models\Post;

$post = Post::find($id);

// Replaces $post->updated_at = now(); $post->save();
$post->touch();
```

- Updates updated_at column to current timestamp and saves model
- Triggers cascading timestamp touches defined on $touches parent relations
- Fires model saving and saved event listeners
