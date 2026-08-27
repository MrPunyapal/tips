---
category: "Laravel"
tags: ["Laravel", "Authorization", "Gates", "Debugging"]
date: "2024-01-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Inspect Authorization Responses and Policy Denial Reasons with Gate::inspect()

> Use Gate::inspect() to retrieve detailed Response objects explaining why an authorization check was permitted or denied.

Standard `Gate::allows()` and `Gate::denies()` return a simple boolean. When a policy check fails, you often need to display the exact explanation (such as "Your trial expired" vs "Insufficient permissions") to the user.

`Gate::inspect()` returns a full `Illuminate\Auth\Access\Response` object.

## Returning Custom Error Messages in Policies

```php
namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function update(User $user, Project $project): Response
    {
        if ($project->is_archived) {
            return Response::deny('This project is archived and cannot be modified.');
        }

        if ($user->id !== $project->owner_id) {
            return Response::deny('You do not own this project.');
        }

        return Response::allow();
    }
}
```

## Inspecting the Response

```php
use Illuminate\Support\Facades\Gate;

$response = Gate::inspect('update', $project);

if ($response->allowed()) {
    // Authorized to proceed
} else {
    // Retrieve custom denial message
    echo $response->message();
    // e.g. "This project is archived and cannot be modified."
}
```

## Summary

- Returns an `Illuminate\Auth\Access\Response` instance with `->allowed()`, `->denied()`, and `->message()`.
- Allows policy methods to communicate specific failure reasons to users and API responses.
- Eliminates guesswork when debugging authorization denials.
