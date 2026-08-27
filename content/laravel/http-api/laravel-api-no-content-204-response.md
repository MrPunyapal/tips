---
category: "Laravel"
tags: ["Laravel", "API", "HTTP", "REST"]
date: "2023-05-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Return Standard HTTP 204 No Content Responses with response()->noContent()

> Use response()->noContent() in RESTful API destroy and update endpoints to return semantic HTTP 204 responses cleanly.

In REST API design, when a resource deletion (`DELETE`) or background update successfully completes without returning a response body, returning HTTP 204 No Content is the standard convention.

Instead of writing `return response('', 204);`, Laravel provides the expressive `response()->noContent()` helper.

## Basic Usage in API Controllers

```php
use App\Models\Post;
use Illuminate\Http\Response;

public function destroy(Post $post): Response
{
    $post->delete();

    // Returns HTTP 204 with an empty response body
    return response()->noContent();
}
```

## Custom Status Codes and Headers

`noContent()` accepts a custom status code as the first argument (defaults to 204) and response headers as the second:

```php
return response()->noContent(204, [
    'X-Action-Completed' => 'true',
]);
```

## Summary

- Returns an empty `Illuminate\Http\Response` with HTTP status code 204.
- Follows RESTful API standards for resource deletions and state updates.
- Expressive and self-documenting syntax for backend API controllers.
