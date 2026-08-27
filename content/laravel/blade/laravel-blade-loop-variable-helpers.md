---
category: "Laravel"
tags: ["Laravel", "Blade", "HTML", "Templates"]
date: "2024-01-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Blade"
---

# Master Blade Foreach Iteration with the $loop Variable

> Leverage Laravel's automatic $loop variable inside @foreach blocks to access iteration index, first/last status, even/odd flags, and parent loop context.

Inside every Blade `@foreach` loop, Laravel automatically injects a `$loop` object providing rich metadata about the current iteration without maintaining manual counter variables.

## Key $loop Properties

```blade
@foreach ($users as $user)
    <div class="{{ $loop->first ? 'bg-primary' : '' }} {{ $loop->even ? 'row-alt' : '' }}">
        <span>#{{ $loop->iteration }}</span>
        <h3>{{ $user->name }}</h3>

        @if ($loop->last)
            <p>Total records: {{ $loop->count }}</p>
        @endif
    </div>
@endforeach
```

## Full $loop Property Reference

| Property | Description |
|---|---|
| `$loop->index` | The index of the current iteration (0-indexed). |
| `$loop->iteration` | The current iteration count (1-indexed). |
| `$loop->remaining` | The number of iterations remaining. |
| `$loop->count` | The total number of items in the array being iterated. |
| `$loop->first` | Whether this is the first iteration. |
| `$loop->last` | Whether this is the last iteration. |
| `$loop->even` | Whether this is an even iteration. |
| `$loop->odd` | Whether this is an odd iteration. |
| `$loop->depth` | The nesting level of the current loop. |
| `$loop->parent` | The `$loop` variable of the parent loop when nested. |

## Accessing Parent Loops in Nested Iterations

```blade
@foreach ($categories as $category)
    <h2>{{ $category->name }}</h2>

    @foreach ($category->posts as $post)
        {{-- Access parent category loop metadata --}}
        <p>Category #{{ $loop->parent->iteration }} - Post #{{ $loop->iteration }}: {{ $post->title }}</p>
    @endforeach
@endforeach
```

## Summary

- Automatically available in all Blade `@foreach` loops with zero setup.
- `$loop->iteration` provides 1-indexed numbering for UI tables.
- `$loop->parent` provides full access to enclosing loops in nested hierarchies.
