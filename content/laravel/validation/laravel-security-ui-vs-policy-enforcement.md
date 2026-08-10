---
category: "Laravel"
tags: ["Laravel", "Security", "Policies"]
date: "2026-06-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Validation"
---

# UI Controls Are Not Security Layers: Always Enforce Policies

> Hiding buttons in Blade or Vue does not restrict access. Always enforce authorization policies in controller or request layers.

Hiding an edit button using @can or v-if only modifies visual presentation. Attackers can submit HTTP requests directly to backend endpoints. Always enforce authorization logic in backend controllers or form requests.

```php
// Blade UI (Visual convenience only)
@can('update', $post)
    <a href="{{ route('posts.edit', $post) }}">Edit Post</a>
@endcan

// Controller Action (Actual Security Layer)
public function update(UpdatePostRequest $request, Post $post)
{
    $this->authorize('update', $post); // Enforces server-side security
    $post->update($request->validated());
}
```

- UI directive checks like @can are user-experience features, not security guards
- Always enforce $this->authorize() or Policy checks in backend controllers
- Prevents unauthorized HTTP request payload tampering
