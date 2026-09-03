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

AI coding agents (Cursor, Claude Code, Devin) are now part of regular development workflows. Laravel provides first-party tools to detect agents and optimize terminal output for LLM parsing.

---

## Agent Detection

```php
use Laravel\AgentDetector\Facades\AgentDetector;

$detection = AgentDetector::detect();

if ($detection->isAgent) {
    // Inspect agent name (e.g. 'claude-code', 'cursor', 'devin')
    logger()->info('AI agent session', ['agent' => $detection->name]);
}
```

---

## How PAO Works

- **First-Party**: Ships with PAO (`laravel/pao`) included by default in new Laravel applications.
- **Output Optimization**: PAO automatically switches verbose Artisan CLI output into compact JSON when an agent is detected.
- **Human Invariant**: Human developers see standard terminal formatting with zero workflow change.
