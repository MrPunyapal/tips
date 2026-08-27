---
category: "Laravel"
tags: ["Laravel", "Queue", "Error Handling", "Clean Code"]
date: "2026-01-14"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Automatically Discard Orphaned Queue Jobs with deleteWhenMissingModels

> Use the $deleteWhenMissingModels property on queued jobs to silently discard jobs if their referenced database records were deleted before execution.

When an Eloquent model is passed to a queued job and a user deletes that record before the worker picks up the job, Laravel throws a `ModelNotFoundException`, causing the job to fail and populate the `failed_jobs` table.

Setting `$deleteWhenMissingModels = true` instructs the worker to delete the job silently.

## Enabling on Job Classes

```php
namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePostThumbnailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    // Silently delete job if the Post model was deleted from database
    public bool $deleteWhenMissingModels = true;

    public function __construct(public Post $post) {}

    public function handle(): void
    {
        // Process thumbnail
    }
}
```

## Summary

- Prevents false-alarm errors in `failed_jobs` and error tracking tools (Sentry, Bugsnag).
- Silently purges queued tasks when parent models are deleted.
- Standard property provided by the `SerializesModels` trait.
