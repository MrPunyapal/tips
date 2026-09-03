---
category: "Laravel"
tags: ["Laravel", "Events", "Queue", "Architecture", "Performance"]
date: "2026-08-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Debounce Queued Event Listeners with #[DebounceFor] in Laravel 13.26

> Laravel 13.26 extends the #[DebounceFor] attribute to queued event listeners, collapsing bursts of repeated events into a single execution based on the latest resource state.

In event-driven applications, domain events like `ProductUpdated` or `DocumentSaved` can fire dozens of times in rapid succession during bulk updates or autosaves.

Running expensive queued listeners (such as rebuilding search indexes or regenerating thumbnails) for every intermediate event creates severe queue churn. Laravel 13.26 extends `#[DebounceFor]` to queued event listeners to delay execution until activity settles.

---

## Implementation Example

Add `#[DebounceFor]` to your listener and define `debounceId()` to isolate debounce windows per resource entity:

```php
namespace App\Listeners;

use App\Events\ProductUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\DebounceFor;

#[DebounceFor(30, maxWait: 120)]
class UpdateProductSearchIndex implements ShouldQueue
{
    // Scope debouncing per model ID so different products process independently
    public function debounceId(ProductUpdated $event): string
    {
        return (string) $event->product->getKey();
    }

    public function handle(ProductUpdated $event): void
    {
        // Runs once with the latest database state after updates settle
        $event->product->searchable();
    }
}
```

---

## Key Rules & Parameters

- **`debounceId()`**: Returns the scoping identifier (such as the model ID). Without this, all incoming `ProductUpdated` events would share a global debounce window across the entire application.
- **`maxWait: 120`**: Sets a hard upper boundary. If a resource receives continuous events, execution is forced at 120 seconds rather than resetting indefinitely.
- **Listener-Specific**: `#[DebounceFor]` applies only to the specific listener where it is declared. Other listeners on the same event still run immediately upon dispatch.
- **Incompatible with `ShouldBeUnique`**: A listener cannot combine `#[DebounceFor]` and `ShouldBeUnique`. `ShouldBeUnique` preserves only the first dispatch and ignores the rest, whereas `#[DebounceFor]` collects bursts to execute the latest state.
