---
category: "JavaScript"
tags: ["Alpine.js", "JavaScript", "Frontend"]
date: "2025-07-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Combine Multiple Alpine.data Objects Inside a Single x-data

> Spread multiple Alpine.data component factories into a single x-data directive to compose modular frontend state.

When a single UI element requires state from multiple Alpine components (e.g. dropdown state + search state), use spread syntax inside x-data to merge component factories.

```html
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('dropdown', () => ({ open: false, toggle() { this.open = !this.open } }));
    Alpine.data('search', () => ({ query: '', clear() { this.query = '' } }));
  });
</script>

<!-- Merge multiple data objects via spread operator -->
<div x-data="{ ...dropdown(), ...search() }">
  <input x-model="query">
  <button @click="toggle()">Toggle Menu</button>
</div>
```

- Composes multiple Alpine.data component factories into one container
- Keeps JavaScript state definitions modular and reusable
- Prevents deeply nested wrapper div structures in HTML markup
