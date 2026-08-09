---
category: "Laravel"
tags: ["Laravel", "Logging", "Configuration"]
date: "2026-07-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Monthly Log Rotation with Laravel 13.23

> Laravel 13.23 adds a built-in `monthly` logging driver that rotates log files once per month instead of daily, reducing log directory clutter for stable applications.

Daily log rotation creates hundreds of log files over time. If your application has moderate log volume, the `monthly` driver keeps logs organized into one file per month (e.g. `laravel-2026-08.log`).

### Configuration

```php
// config/logging.php
'channels' => [
    'monthly' => [
        'driver' => 'monthly',
        'path'   => storage_path('logs/laravel.log'),
        'level'  => env('LOG_LEVEL', 'debug'),
        'days'   => 12, // Retain 12 months of logs
    ],
],
```

### Environment Setup

```bash
LOG_CHANNEL=monthly
```

- One log file per month (e.g. `laravel-2026-08.log`)
- `days` parameter controls how many months of logs to retain
- Ideal for production apps with moderate log volume
