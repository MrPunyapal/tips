---
category: "Laravel"
tags: ["Laravel", "Artisan", "AI"]
date: "2026-07-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Protect Custom Artisan Commands from AI Agents with Prohibitable

> Use Laravel's Prohibitable trait on custom Artisan commands to selectively block AI agents from running destructive domain operations.

While DB::prohibitDestructiveCommands guards core migrations, custom commands need explicit checks so AI agents don't accidentally run destructive domain actions.

```php
use App\Console\Commands\DeleteInactiveUsersCommand;
use Laravel\AgentDetector\Facades\AgentDetector;

DeleteInactiveUsersCommand::prohibit(AgentDetector::detect()->isAgent);
```

- Prohibitable trait adds ::prohibit() and ->isProhibited() to Artisan commands
- Combine with AgentDetector to let humans run commands while blocking AI agents
- Protects application-specific operations beyond database migrations
