---
category: "Laravel"
tags: ["Laravel", "Artisan", "CLI", "Developer Experience"]
date: "2023-06-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Protect Destructive Artisan Commands with Interactive Confirmation Prompts

> Use $this->confirm() and Laravel Prompts inside Artisan commands to require explicit confirmation before executing destructive operations.

When creating custom Artisan CLI commands that delete records, clear caches, or synchronize production databases, executing accidentally without confirmation can lead to severe data loss.

Laravel provides built-in confirmation helpers on all command classes.

## Using $this->confirm() in Commands

```php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PruneInactiveUsersCommand extends Command
{
    protected $signature = 'users:prune-inactive {--force : Bypass confirmation}';
    protected $description = 'Permanently delete inactive users';

    public function handle(): int
    {
        $count = User::where('last_active_at', '<', now()->subYears(2))->count();

        if ($count === 0) {
            $this->info('No inactive users found.');
            return self::SUCCESS;
        }

        // Require confirmation unless --force flag is passed
        if (! $this->option('force') && ! $this->confirm("Are you sure you want to delete {$count} users?", false)) {
            $this->warn('Operation cancelled.');
            return self::SUCCESS;
        }

        User::where('last_active_at', '<', now()->subYears(2))->delete();
        $this->info("Successfully deleted {$count} users.");

        return self::SUCCESS;
    }
}
```

## Using Modern Laravel Prompts

Laravel also includes Laravel Prompts for interactive terminal UIs:

```php
use function LaravelPromptsconfirm;

$confirmed = confirm(
    label: 'Do you want to proceed with database synchronization?',
    default: false,
    yes: 'Yes, synchronize now',
    no: 'Cancel'
);
```

## Summary

- Protects database records and remote services from accidental command execution.
- `$this->confirm('question', default: false)` defaults safely to rejection.
- Supports `--force` options for non-interactive automated CI/CD pipelines.
