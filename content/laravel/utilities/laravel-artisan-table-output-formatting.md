---
category: "Laravel"
tags: ["Laravel", "Artisan", "CLI", "Formatting"]
date: "2023-07-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Format CLI Data into Clean Terminal Tables with $this->table()

> Use $this->table() inside Artisan commands to render structured tabular data with clean headers and automatic column alignment.

When building diagnostic Artisan commands (such as inspecting system health, viewing user roles, or auditing feature flags), dumping raw text or JSON into the terminal makes output hard to read.

The `table()` method renders ASCII tables in the CLI.

## Rendering Tables in Artisan Commands

```php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListAdminsCommand extends Command
{
    protected $signature = 'users:admins';
    protected $description = 'List all administrator accounts';

    public function handle(): int
    {
        $headers = ['ID', 'Name', 'Email', 'Role', 'Created At'];

        $rows = User::where('is_admin', true)->get()->map(function (User $user) {
            return [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                $user->created_at->format('Y-m-d H:i'),
            ];
        });

        $this->table($headers, $rows);

        return self::SUCCESS;
    }
}
```

## Terminal Output Example

```text
+----+---------------+---------------------+--------+------------------+
| ID | Name          | Email               | Role   | Created At       |
+----+---------------+---------------------+--------+------------------+
| 1  | Punyapal Shah | punyapal@dev.local  | Owner  | 2026-01-15 08:30 |
| 2  | Punyapal Shah   | punyapal@example.com      | Admin  | 2026-02-01 10:15 |
+----+---------------+---------------------+--------+------------------+
```

## Summary

- Renders formatted ASCII tables directly in the terminal.
- Accepts arrays of headers and rows or Eloquent collections mapped to arrays.
- Ideal for custom debugging, status checks, and data inspection commands.
