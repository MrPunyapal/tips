---
category: "Laravel"
tags: ["Laravel", "AI", "DevOps"]
date: "2026-07-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Configuration"
---

# Detect AI Agents in Laravel with AgentDetector and PAO

> Laravel's AgentDetector detects whether an AI coding agent is interacting with your app, and PAO optimizes CLI output for agents.

AI agents are part of modern dev workflows, and Laravel provides first-party tools to handle them cleanly by detecting agents and switching terminal outputs to compact JSON.

```php
use Laravel\AgentDetector\Facades\AgentDetector;

if (AgentDetector::detect()->isAgent) {
    logger()->info('AI agent detected', ['agent' => AgentDetector::detect()->name]);
}
```

- AgentDetector ships with PAO (laravel/pao) by default in new apps
- Detects agents via environment variables and file markers
- PAO replaces verbose CLI output with compact JSON for agents
