---
category: "Laravel"
tags: ["Laravel", "Queue", "DevOps"]
date: "2025-12-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Optimize Queue Worker Polling Overheads in Production

> Use queue:work with appropriate sleep configuration or Redis blocking pops to reduce database CPU polling overheads.

Queue workers set to poll databases without sleep configs execute continuous SELECT queries, driving database CPU usage up. Configure appropriate sleep intervals or use Redis queue drivers.

```bash
# Wait 3 seconds when queue is empty before polling again
php artisan queue:work --sleep=3 --tries=3 --timeout=90
```

- --sleep=3 pauses worker polling when no jobs are available
- Reduces CPU usage and database query loads on idle queue workers
- Redis queue driver uses blocking pop operations for instant zero-polling dispatch
