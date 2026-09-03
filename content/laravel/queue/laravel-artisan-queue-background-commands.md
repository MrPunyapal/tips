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

Running heavy Artisan commands (such as generating bulk reports, clearing large cache stores, or sending system audits) inside HTTP controller requests blocks the user web server thread.

`Artisan::queue()` dispatches the command directly to background queue workers.

---

## Code Example

```php
use Illuminate\Support\Facades\Artisan;

// 1. Pushes command execution to default background queue
Artisan::queue('reports:generate', [
    '--user' => $user->id,
]);

// 2. Specify dedicated connection and queue worker
Artisan::queue('reports:generate', ['--user' => $user->id])
    ->onConnection('redis')
    ->onQueue('exports');
```

---

## Key Benefits

- **Non-Blocking**: Prevents HTTP timeouts by running commands asynchronously in background queue workers.
- **Routing Control**: Chain `onConnection()` and `onQueue()` to route tasks to dedicated worker pools.
- **Argument Support**: Accepts command arguments and flag options in standard key-value arrays.
