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

While `DB::prohibitDestructiveCommands()` protects core database migrations, custom Artisan commands (such as purging inactive tenants or deleting old records) require their own guard so AI coding agents do not execute them automatically.

---

## 1. Add Prohibitable to the Command Class

Add the `Illuminate\Console\Prohibitable` trait to your custom command:

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Prohibitable;

class DeleteInactiveUsersCommand extends Command
{
    use Prohibitable;

    protected $signature = 'users:purge-inactive';

    public function handle(): int
    {
        if ($this->isProhibited()) {
            $this->error('This command is prohibited in this environment.');
            return Command::FAILURE;
        }

        // Execution logic...
        return Command::SUCCESS;
    }
}
```

---

## 2. Prohibit in AppServiceProvider

Prohibit execution when an AI coding agent is detected:

```php
namespace App\Providers;

use App\Console\Commands\DeleteInactiveUsersCommand;
use Illuminate\Support\ServiceProvider;
use Laravel\AgentDetector\Facades\AgentDetector;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Humans can run locally; AI agents are blocked
        DeleteInactiveUsersCommand::prohibit(AgentDetector::detect()->isAgent);
    }
}
```

---

## Key Points

- **Trait Mechanics**: Adding `use Prohibitable;` provides both the static `::prohibit()` configuration method and the instance `->isProhibited()` check.
- **Granular Control**: Protects destructive application-specific commands without blanket blocking all Artisan tools.
