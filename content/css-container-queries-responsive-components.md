---
category: "CSS"
tags: ["CSS", "Responsive", "Frontend"]
date: "2023-02-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Use CSS Container Queries for Modular Component Layouts

> Use CSS Container Queries (@container) to adjust component layouts based on parent container width rather than viewport size.

Media queries (@media) check total screen width, which breaks when a component is placed inside narrow sidebars versus wide main content areas. Container queries adjust styles based on element parent container width.

```css
/* Define container context on parent */
.card-container {
  container-type: inline-size;
}

/* Adjust card layout based on container width */
@container (min-width: 400px) {
  .card {
    display: flex;
    flex-direction: row;
  }
}
```

- Adapts component layouts based on parent container dimensions instead of viewport width
- Allows building truly self-contained, context-aware UI components
- Supported natively in all modern web browsers
