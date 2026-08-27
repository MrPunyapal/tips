---
category: "Laravel"
tags: ["Laravel", "Blade", "HTML", "Clean Code"]
date: "2024-01-24"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Clean Up HTML Forms with Blade's @checked and @selected Directives

> Replace cumbersome ternary operators in HTML form inputs with Laravel's @checked and @selected Blade directives.

Conditionally adding `checked` or `selected` attributes to checkboxes, radio buttons, and select dropdowns usually requires repetitive inline PHP or ternary expressions:

```html
<!-- Old verbose syntax -->
<input type="checkbox" name="active" value="1" {{ old('active', $user->is_active) ? 'checked' : '' }}>

<option value="pro" {{ old('plan', $user->plan) === 'pro' ? 'selected' : '' }}>Pro</option>
```

Laravel provides `@checked` and `@selected` to express this cleanly.

## Using @checked for Checkboxes and Radio Buttons

```blade
<input 
    type="checkbox" 
    name="newsletter" 
    value="1" 
    @checked(old('newsletter', $user->subscribed_to_newsletter)) 
/>
```

If the evaluated expression is truthy, Laravel outputs `checked="checked"`. If falsy, nothing is rendered.

## Using @selected for Dropdown Options

```blade
<select name="status">
    <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
    <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
    <option value="archived" @selected(old('status', $post->status) === 'archived')>Archived</option>
</select>
```

## Also: @disabled and @readonly

Laravel also provides `@disabled` and `@readonly` directives:

```blade
<button type="submit" @disabled($cart->isEmpty())>
    Checkout
</button>
```

## Summary

- Eliminates verbose ternary operators in HTML templates.
- Automatically handles boolean truth-testing and outputs appropriate HTML attributes.
- Works across checkboxes (`@checked`), select menus (`@selected`), and buttons (`@disabled`).
