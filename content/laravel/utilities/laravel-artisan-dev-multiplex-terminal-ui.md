---
category: "Laravel"
tags: ["Laravel", "Artisan", "DX", "Tooling"]
date: "2026-08-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Terminal UI Upgrade for php artisan dev in Laravel 13.25

> Laravel 13.25 upgrades php artisan dev with @laravel/multiplex to provide a tabbed terminal UI with process search, restarts, and log management.

Running `composer run dev` or `php artisan dev` manages multiple development processes in parallel, such as the PHP development server, queue workers, log streaming with Pail, and asset compilation with Vite.

Previously, all active processes wrote their output concurrently into a single shared scrolling terminal window, making it difficult to isolate specific server logs or track build errors.

Laravel 13.25 upgrades `php artisan dev` to use `@laravel/multiplex` as its terminal interface on supported platforms, giving each development process its own dedicated tab and interactive controls.

## What the Multiplex UI Provides

The updated terminal interface organizes concurrent processes into distinct views and introduces several process management features:

- **Tabbed process views**: Each registered command (HTTP server, Vite, Queue, Pail) runs in its own tab, selectable with keyboard shortcuts.
- **Searchable output**: Filter and search through process logs directly in the terminal interface.
- **Individual process restarts**: Restart a specific process (such as a queue worker) without restarting your entire development environment.
- **Log clearing**: Clear the log buffer for any individual process window.
- **Automatic crash recovery**: Processes that fail or crash can automatically restart in the background.
- **Exit log preservation**: When stopping `artisan dev`, buffered logs from each process are printed to the main terminal so recent errors are preserved.

```text
┌─────────────────────────────────────────────────────────┐
│  Laravel Dev                                            │
├──────────────┬──────────────────────────────────────────┤
│ ● Server     │                                          │
│ ● Queue      │   [active process output]                │
│ ● Pail       │                                          │
│ ● Vite       │   ...                                    │
├──────────────┴──────────────────────────────────────────┤
│ Search  │ Restart │ Clear │ ...                         │
└─────────────────────────────────────────────────────────┘
```

## CLI Usage and Output Modes

You can run `artisan dev` with flags to customize output formatting and runtime behavior:

```bash
# Start all dev processes with tabbed Multiplex UI
composer run dev

# Or run directly via Artisan
php artisan dev

# Fall back to interleaved streaming output
php artisan dev --stream

# Prepend timestamps to log output lines
php artisan dev --timestamps

# Prevent crashed processes from automatically restarting
php artisan dev --no-restart
```

`php artisan dev` supports three distinct output modes:
1. `tabs` (default): Interactive tabbed interface powered by `@laravel/multiplex`.
2. `stream`: Traditional interleaved terminal stream where all process logs appear in one combined feed.
3. `inline`: Plain non-interactive output, activated automatically when running without an interactive TTY (such as in CI or scripted environments).

## Configuring DevCommands in PHP

In addition to CLI flags, you can configure default development process behavior globally using the `Illuminate\Foundation\DevCommands` facade:

```php
use Illuminate\Foundation\DevCommands;

// Configure runtime options introduced in Laravel 13.25
DevCommands::stream();
DevCommands::withTimestamps();
DevCommands::disableAutoRestart();
DevCommands::bufferSize(5000);
```

These configuration helpers complement existing process registration methods (such as `DevCommands::register()`, `artisan()`, `node()`, `only()`, and `except()`), allowing teams to enforce consistent local development defaults across an entire project.

## Platform and Node.js Requirements

The `@laravel/multiplex` interface has specific platform prerequisites:

- **Supported Platforms**: macOS and Linux.
- **Windows Support**: On Windows environments, `php artisan dev` automatically falls back to `concurrently` streaming.
- **Node.js Version**: The Multiplex path requires Node.js v22.13 or newer.