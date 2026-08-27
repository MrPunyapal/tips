---
category: "Laravel"
tags: ["Laravel", "Authorization", "Security", "HTTP"]
date: "2024-07-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Prevent Information Disclosure with Gate::denyAsNotFound()

> Use Response::denyAsNotFound() in policies to return a 404 Not Found response instead of 403 Forbidden, concealing the existence of private resources.

Returning HTTP 403 Forbidden on confidential resources (such as private repositories, draft invoices, or secret project boards) tells attackers that the resource exists, leaking information.

Laravel policies support `Response::denyAsNotFound()` to return a 404 response directly from authorization checks.

## Policy Implementation

```php
namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function view(User $user, Project $project): Response
    {
        if ($project->is_confidential && $project->owner_id !== $user->id) {
            // Returns HTTP 404 instead of 403 Forbidden
            return Response::denyAsNotFound('Project not found.');
        }

        return Response::allow();
    }
}
```

## Summary

- Throws a `NotFoundHttpException` (404) rather than `AuthorizationException` (403).
- Prevents resource enumeration attacks on private URL identifiers.
- Keeps authorization logic centralized inside Policy classes.
