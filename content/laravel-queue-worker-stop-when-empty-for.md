---
category: "Laravel"
tags: ["Laravel","Queue","DevOps"]
date: "2025-11-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Auto-Scale Queue Workers with --stop-when-empty-for in Laravel

> The --stop-when-empty-for option keeps queue workers alive for a specific grace period after the queue empties, preventing rapid process churn during bursty workloads.

Running `php artisan queue:work --stop-when-empty` in serverless environments or container auto-scalers terminates the worker process immediately when no jobs remain. If new jobs arrive seconds later, new processes must spin up continuously.

The `--stop-when-empty-for` option adds a configurable idle timeout:

```bash
# Stops worker immediately once queue is empty
php artisan queue:work --stop-when-empty

# Keeps worker running for 60 idle seconds before exiting
php artisan queue:work --stop-when-empty-for=60
```

- Prevents process start/stop churn during intermittent job spikes
- Ideal for AWS ECS, Kubernetes HPA, and Serverless worker instances
- Retains database connection pooling while waiting for trailing jobs
