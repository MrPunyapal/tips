---
category: "Laravel"
tags: ["Laravel", "Artisan", "Queue"]
date: "2024-01-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queue"
---

# Queue Heavy Artisan Commands with Artisan::queue()

> Use Artisan::queue() to push long-running Artisan commands to background queues instead of blocking HTTP requests.

Running heavy Artisan commands like database backups or report generators inside HTTP controller requests blocks the user web server thread. Artisan::queue() dispatches the command to queue workers.

```php
use Illuminate\Support\Facades\Artisan;

// Pushes Artisan command execution to default background queue
Artisan::queue('reports:generate', [
    '--user' => $user->id,
]);
```

- Dispatches command execution as a background queue job
- Prevents HTTP request timeouts during long-running tasks
- Accepts command arguments and option arrays
