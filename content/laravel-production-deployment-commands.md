---
category: "Laravel"
tags: ["Laravel", "Deployment", "DevOps"]
date: "2023-11-09"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Essential Laravel Production Deployment Command Sequence

> Execute standard caching and optimization Artisan commands during production CI/CD deployment scripts.

Deploying Laravel applications without caching routes, views, and configuration degrades performance. Run this standard production optimization command sequence during deployment.

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan queue:restart
```

- migrate --force runs database migrations without interaction prompts
- config:cache, route:cache, and view:cache pre-compile core assets
- queue:restart signals background workers to reload updated code
