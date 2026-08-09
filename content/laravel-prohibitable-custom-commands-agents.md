---
category: "Laravel"
tags: ["Laravel", "Artisan", "AI", "Security"]
date: "2026-07-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Protect Custom Artisan Commands from AI Agents with Prohibitable

> Use Laravel's `Prohibitable` trait on custom Artisan commands to selectively block AI agents from running destructive application-specific operations.

While `DB::prohibitDestructiveCommands` guards core migrations, custom commands need explicit checks so AI agents don't accidentally run destructive domain actions.

### Implementing Prohibitable

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;

class DeleteInactiveUsersCommand extends Command
{
    use Prohibitable;

    protected $signature = 'users:delete-inactive';
    protected $description = 'Permanently delete users inactive for 90+ days';

    public function handle(): int
    {
        if ($this->isProhibited()) {
            $this->error('This command is currently prohibited.');
            return Command::FAILURE;
        }

        // Deletion logic...
        return Command::SUCCESS;
    }
}
```

### Conditionally Prohibiting Execution

```php
use App\Console\Commands\DeleteInactiveUsersCommand;
use Laravel\AgentDetector\Facades\AgentDetector;

// Humans can run locally, AI agents are blocked
DeleteInactiveUsersCommand::prohibit(
    AgentDetector::detect()->isAgent
);
```

- `Prohibitable` trait adds `::prohibit()` and `->isProhibited()` to any Artisan command
- Combine with `AgentDetector` to let humans run commands while blocking AI agents
- Protects application-specific operations beyond standard database migrations
