---
category: "JavaScript"
tags: ["JavaScript","Tailwind CSS","DOM"]
date: "2023-12-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Frameworks"
---

# Build Framework-Free Dropdowns with Tailwind and nextElementSibling

> Create lightweight, zero-dependency toggle dropdowns using inline JavaScript nextElementSibling calls with Tailwind CSS hidden classes.

For simple marketing sites or static pages where full JavaScript frameworks like Alpine.js or Vue are not installed, you can toggle dropdown visibility using native DOM methods.

By calling `this.nextElementSibling.classList.toggle('hidden')`, the button toggles the adjacent menu element directly:

```html
<div class="relative inline-block text-left ms-2">
    <button type="button" 
            onclick="this.nextElementSibling.classList.toggle('hidden')"
            class="bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded inline-flex items-center">
        <span>Languages &#x25BE;</span>
    </button>
    <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden"
         role="menu">
        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="#">English</a>
        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="#">French</a>
        <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="#">Gujarati</a>
    </div>
</div>
```

- Adds interactive dropdown behavior with zero npm dependencies
- Works natively across all modern desktop and mobile browsers
- Clean solution for simple landing page navigation elements
