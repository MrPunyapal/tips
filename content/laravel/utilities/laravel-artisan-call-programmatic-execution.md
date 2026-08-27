---
category: "Laravel"
tags: ["Laravel", "Artisan", "CLI", "Architecture"]
date: "2023-11-08"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Execute Artisan Commands Programmatically with Artisan::call() and queue()

> Use Artisan::call() and Artisan::queue() to run console commands, clear caches, and trigger backups from HTTP controllers and background jobs.

When automating maintenance tasks, administrative dashboards, or deployment webhooks, you often need to run Artisan commands from within PHP code.

Laravel provides the `Artisan` facade to execute commands programmatically.

## Running Synchronous Commands

```php
use Illuminate\Support\Facades\Artisan;

// Run command and pass arguments/options
$exitCode = Artisan::call('cache:clear');

// Passing arguments and options as an array
$exitCode = Artisan::call('mail:send', [
    'user'    => 42,
    '--queue' => 'default',
]);

// Retrieve command console output
$output = Artisan::output();
```

## Running Asynchronous Commands in the Background with queue()

To prevent long-running commands from blocking HTTP web requests:

```php
// Pushes command execution to the background queue worker
Artisan::queue('reports:generate-annual-pdf', [
    'year' => 2026,
]);
```

## Summary

- Executes registered Artisan commands from anywhere in the application.
- `Artisan::output()` retrieves the string output of the executed command.
- `Artisan::queue()` delegates heavy CLI tasks to background queue workers.
