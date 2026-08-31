---
category: "Laravel"
tags: ["Laravel", "Database", "Security", "Exceptions"]
date: "2026-08-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Mask Query Bindings in Laravel Exception Messages

> Keep query bindings out of `QueryException` messages while retaining access to the actual bindings.

When a database query fails, Laravel's `QueryException` message can include the values that were bound to the query.

That can be useful while debugging, but it can also mean sensitive values end up in logs, error trackers, or other places where exception messages are stored.

Laravel 13.27 adds an option to mask those bindings in exception messages.

## Before

A failed query can produce an exception message containing the actual value:

```text
SQL: select * from `users` where `email` = 'john@example.com'
```

That means the query's bound values become part of the exception message.

## Laravel 13.27

Enable binding masking on your database connection:

```php
// config/database.php

'connections' => [
    'mysql' => [
        // ...

        'mask_bindings_in_exception_messages' => env('DB_MASK_BINDINGS', false),
    ],
];
```

Now the exception message keeps the placeholder instead of interpolating the binding:

```text
SQL: select * from `users` where `email` = ?
```

## The bindings are still available

Masking the exception message does not remove the bindings from the query.

You can still access them separately through:

```php
$exception->getBindings();
```

This gives you a useful separation:

```text
Exception message
    ↓
SQL with ? placeholders

Exception bindings
    ↓
Actual values
```

So your error message can avoid exposing query values while the actual bindings remain available when you explicitly need them.

## Why this is useful

This can be especially useful when exceptions are sent to:

- application logs
- error tracking services
- APM systems
- failed job records
- other external monitoring systems

If query values can contain sensitive or personal information, keeping them out of the exception message reduces the chance of accidentally exposing them through your error reporting pipeline.

## Takeaway

Laravel 13.27 gives you more control over what appears in `QueryException` messages.

If you don't need actual query bindings embedded in your exception messages, enable:

```php
'mask_bindings_in_exception_messages' => env('DB_MASK_BINDINGS', false),
```

You still have access to the bindings separately through `getBindings()`.
