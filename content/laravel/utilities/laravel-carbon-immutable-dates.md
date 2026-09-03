---
category: "Laravel"
tags: ["Laravel", "Carbon", "Dates"]
date: "2026-09-03"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Use Immutable Dates by Default in Laravel

> Make Laravel use `CarbonImmutable` by default so date calculations don't unexpectedly modify the original date.

Carbon dates are mutable by default, so date operations can unexpectedly change the original instance.

```php
$date = now();

$tomorrow = $date->addDay();

echo $date->toDateString();      // 2026-09-04
echo $tomorrow->toDateString();  // 2026-09-04
```

`CarbonImmutable` returns a new instance instead:

```php
use Carbon\CarbonImmutable;

$date = CarbonImmutable::now();

$tomorrow = $date->addDay();

echo $date->toDateString();      // 2026-09-03
echo $tomorrow->toDateString();  // 2026-09-04
```

## Make It the Laravel Default

You can configure Laravel's date factory to use `CarbonImmutable`:

```php
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;

Date::use(CarbonImmutable::class);
```

Now existing helpers such as `now()` use immutable dates:

```php
$date = now();

$tomorrow = $date->addDay();

echo $date->toDateString();      // 2026-09-03
echo $tomorrow->toDateString();  // 2026-09-04
```

This means you can keep using Laravel's normal date helpers without manually calling `CarbonImmutable::now()` everywhere.

## Migrating an Existing Codebase

When moving an existing application to immutable dates, look for code that relies on Carbon mutating the original instance.

```php
$date = now();

$date->addDay();

echo $date->toDateString();  // Mutable: 2026-09-04
```

With immutable dates, assign the returned instance when you want to change the variable:

```php
$date = now();

$date = $date->addDay();

echo $date->toDateString();  // 2026-09-04
```

For reusable code, prefer `CarbonInterface` when you do not specifically need mutable `Carbon`:

```php
use Carbon\CarbonInterface;

function processDate(CarbonInterface $date): void
{
    // ...
}
```

### Using Rector

If you are migrating a large codebase, Rector can help with automated refactoring, but the exact Carbon migration rule should be checked against the Rector version installed in the project rather than assuming a rule name.

You can inspect the rules available to your project with:

```bash
vendor/bin/rector list-rules
```

Rector also provides an online rule finder for discovering and verifying available rules.

The important distinction is:

- **Rector** can help automate repetitive migration work.
- **`Date::use(CarbonImmutable::class)`** makes immutable dates the Laravel default.

Before enabling this globally, check application and package code that depends on mutable Carbon behavior.
