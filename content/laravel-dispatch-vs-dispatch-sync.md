---
category: "Laravel"
tags: ["Laravel","Queue"]
date: "2023-09-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Understand dispatch() vs dispatch_sync() in Laravel Jobs

> Know when to push background jobs to asynchronous queues with dispatch() versus executing them immediately in the current HTTP request process using dispatch_sync().

Laravel provides helper functions for pushing jobs to workers or executing them synchronously in the current process.

Understanding the difference makes sure background operations execute at the right time:

```php
use App\Jobs\ProcessPdfExport;
use App\Jobs\UpdateUserStatus;

// Async: Pushes job to queue driver (Redis/Database) for worker execution
dispatch(new ProcessPdfExport($document));

// Sync: Executes job immediately inside current HTTP request process
dispatch_sync(new UpdateUserStatus($user));
```

- `dispatch()`: Non-blocking, offloads long-running tasks to queue workers
- `dispatch_sync()`: Blocking, runs immediately inline without requiring an active queue worker
- Handy for CLI commands and testing where immediate execution is required
