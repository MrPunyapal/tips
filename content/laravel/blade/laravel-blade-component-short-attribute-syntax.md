---
category: "Laravel"
tags: ["Laravel", "Blade", "Components", "Clean Code"]
date: "2023-08-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Use Short Attribute Syntax for Blade Components

> Replace verbose :user="$user" bindings with short attribute syntax :$user when passing PHP variables to Blade components.

When rendering Blade components and passing variables that share the same name as the component prop (such as passing `$user` to `:user="$user"` or `$post` to `:post="$post"`), repeating the variable name adds visual boilerplate.

Laravel supports short attribute syntax for component bindings.

## Before vs. After

```blade
{{-- Traditional verbose syntax --}}
<x-profile-card 
    :user="$user" 
    :post="$post" 
    :commentsCount="$commentsCount" 
/>

{{-- Clean short attribute syntax --}}
<x-profile-card 
    :$user 
    :$post 
    :$commentsCount 
/>
```

## Inside the Component

The component receives the variables as standard props:

```blade
{{-- resources/views/components/profile-card.blade.php --}}
@props(['user', 'post', 'commentsCount'])

<div class="card">
    <h3>{{ $user->name }}</h3>
    <p>{{ $post->title }}</p>
    <span>{{ $commentsCount }} comments</span>
</div>
```

## Summary

- `:$variable` is identical to `:variable="$variable"`.
- Mirrors modern JavaScript and Vue shorthand attribute bindings.
- Makes template files cleaner and faster to read.
