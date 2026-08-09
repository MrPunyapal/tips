---
category: "Laravel"
tags: ["Laravel", "Events", "Architecture"]
date: "2023-09-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Avoid auth() and session() Inside Event Listeners

> Pass authenticated user objects or session data explicitly inside Event payloads instead of relying on global session helpers in listeners.

Accessing global auth() or session() helpers inside event listeners breaks if the listener is pushed to a background queue where HTTP context is absent. Pass required data in event payloads.

```php
namespace App\Events;

use App\Models\User;

class UserRegistered
{
    // Pass user model explicitly in event constructor
    public function __construct(public User $user) {}
}
```

- Queued event listeners execute outside HTTP request cycles with no session context
- Pass authenticated user models and contextual data inside Event constructors
- makes sure event listeners are decoupled and safely queueable
