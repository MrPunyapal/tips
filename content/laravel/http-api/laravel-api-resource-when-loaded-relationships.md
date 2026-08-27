---
category: "Laravel"
tags: ["Laravel", "API", "Eloquent", "Performance"]
date: "2023-05-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Prevent N+1 Queries in API Resources with whenLoaded()

> Use whenLoaded() inside API Resources to include related models only when they have been explicitly eager-loaded by the controller.

When transforming Eloquent models into JSON responses using API Resources, referencing relationships directly (`'author' => new UserResource($this->author)`) causes lazy-loading queries if the controller did not eager-load the relation, triggering N+1 query bottlenecks.

`whenLoaded()` includes the relationship only if it was already loaded.

## Using whenLoaded in API Resources

```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,

            // Included in JSON only if $post->relationLoaded('author') is true
            'author' => new UserResource($this->whenLoaded('author')),

            // Included only if comments were eager-loaded
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
```

## Controller Usage

```php
// Relationship included without N+1 queries
return PostResource::collection(Post::with('author')->paginate(15));

// Relationship omitted automatically from JSON output
return PostResource::collection(Post::paginate(15));
```

## Summary

- Prevents accidental lazy-loading queries during JSON serialization.
- Keeps API resources reusable across lightweight index listings and detailed show endpoints.
- Automatically omits un-eager-loaded keys from the serialized JSON payload.
