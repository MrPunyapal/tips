---
category: "Laravel"
tags: ["Laravel", "Artisan", "CLI", "Clean Code"]
date: "2024-10-30"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Create Lightweight CLI Tools with Closure Commands in routes/console.php

> Use Artisan::command() in routes/console.php to build focused CLI commands using closures without creating dedicated Command classes.

Creating a full `php artisan make:command` class for simple administrative tasks (like generating report summaries, triggering test webhooks, or syncing a single user) introduces unnecessary file clutter.

Laravel supports closure-based commands in `routes/console.php`.

## Defining Closure Commands

```php
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

Artisan::command('user:promote {user} {--role=admin}', function (User $user) {
    $role = $this->option('role');

    $user->update(['role' => $role]);

    $this->info("Successfully promoted user {$user->email} to {$role}!");
})->purpose('Promote a user to a specific system role');
```

## Dependency Injection and Type Hints

Closure commands support route model binding and service container injection directly in their argument list:

```php
Artisan::command('billing:reconcile', function (PaymentGateway $gateway) {
    $this->comment('Reconciling pending Stripe invoices...');
    $gateway->reconcile();
    $this->info('Done!');
})->purpose('Reconcile pending gateway invoices');
```

## Summary

- Defines console commands using closures in `routes/console.php`.
- Supports model binding, arguments, options, and container injection.
- Keeps simple CLI utilities organized in a single file.
