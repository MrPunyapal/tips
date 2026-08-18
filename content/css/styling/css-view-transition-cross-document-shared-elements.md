---
category: "CSS"
tags: ["CSS", "View Transitions", "Animation", "Frontend"]
date: "2026-08-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Styling"
---

# Smooth Cross-Document View Transitions with @view-transition and Shared Elements

> Enable native cross-document page transitions and smooth shared element animations without JavaScript routing libraries using the CSS @view-transition at-rule and view-transition-name.

Traditional multi-page websites can cause abrupt layout flashes during page navigation. With CSS View Transitions Level 2, modern browsers can animate page transitions across same-origin HTML navigations natively.

## 1. Enable Cross-Document Transitions

Add the `@view-transition` at-rule to your global stylesheet to opt into cross-document transition rendering:

```css
@view-transition {
  navigation: auto;
}
```

## 2. Connect Shared Elements Across Pages

Assign a matching `view-transition-name` to elements that persist across pages, such as active navigation indicators or profile avatars:

```html
<!-- Shared active navigation indicator -->
<span class="active-indicator" style="view-transition-name: active-nav-indicator;"></span>

<!-- Shared profile picture between pages -->
<img src="/avatar.jpg" alt="Profile" style="view-transition-name: profile-avatar;">
```

```text
Page 1 (/): Start
┌───────────────────────────────────────────────────────────┐
│  Home   Services   Projects   Open Source   Tips   Resume │
│  ━━━━                                                     │
└───────────────────────────────────────────────────────────┘
                              ↓
Mid-Transition (Shared Element Morph):
┌───────────────────────────────────────────────────────────┐
│  Home   Services   Projects   Open Source   Tips   Resume │
│               ━━━━━━                                      │
└───────────────────────────────────────────────────────────┘
                              ↓
Page 2 (/projects): Complete
┌───────────────────────────────────────────────────────────┐
│  Home   Services   Projects   Open Source   Tips   Resume │
│                    ━━━━━━━━                               │
└───────────────────────────────────────────────────────────┘
```

## 3. Customize Group Timing and Prevent Ghosting

By default, the browser animates element geometry while cross-fading old and new snapshots. For solid elements like indicator lines or avatars, you can disable the opacity cross-fade so the element physically glides and morphs to its new position:

```css
/* Target the shared element transition group */
::view-transition-group(active-nav-indicator),
::view-transition-group(profile-avatar) {
  animation-duration: 480ms;
  animation-timing-function: cubic-bezier(0.22, 1, 0.36, 1);
  z-index: 1000;
}

/* Prevent cross-fade opacity for clean sliding */
::view-transition-old(active-nav-indicator),
::view-transition-new(active-nav-indicator) {
  animation: none;
  mix-blend-mode: normal;
  height: 100%;
}

/* Respect user motion preferences */
@media (prefers-reduced-motion: reduce) {
  ::view-transition-old(root),
  ::view-transition-new(root),
  ::view-transition-group(active-nav-indicator),
  ::view-transition-group(profile-avatar) {
    animation: none !important;
  }
}
```

## Practical Takeaways

- Delivers smooth page morphing on standard multi-page static sites and Laravel Blade templates.
- Requires zero client-side routing libraries or Single Page Application (SPA) frameworks.
- Functions as progressive enhancement: browsers without View Transition Level 2 support perform standard instant page navigations.
