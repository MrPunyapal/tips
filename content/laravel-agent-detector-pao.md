---
category: "Laravel"
tags: ["Laravel", "AI", "DevOps", "Security"]
date: "2026-07-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Detect AI Agents in Laravel with AgentDetector and PAO

> Laravel's `AgentDetector` detects whether an AI coding agent (Cursor, Claude Code, Devin) is interacting with your app, and PAO optimizes CLI output for agents. Both ship by default with new Laravel installations.

AI agents are part of modern dev workflows, and Laravel provides first-party tools to handle them cleanly.

### Detecting AI Agents

```php
use Laravel\AgentDetector\Facades\AgentDetector;

if (AgentDetector::detect()->isAgent) {
    // Running inside an AI coding agent
    logger()->info('AI agent detected', [
        'agent' => AgentDetector::detect()->name,
    ]);
}
```

### Protecting Destructive Commands

```php
// AppServiceProvider::boot()
use Illuminate\Support\Facades\DB;
use Laravel\AgentDetector\Facades\AgentDetector;

// Block destructive migrations when an AI agent is running,
// but allow humans to use them locally
DB::prohibitDestructiveCommands(
    $this->app->isProduction() || AgentDetector::detect()->isAgent
);
```

- `AgentDetector` ships with PAO (`laravel/pao`), included by default in new Laravel apps
- Detects agents via environment variables and file markers
- PAO replaces verbose CLI output with compact JSON when an agent is detected
- Human developers see no change: PAO only activates for AI agents
