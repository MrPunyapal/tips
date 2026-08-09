---
category: "Laravel"
tags: ["Laravel","Pint","PHP 8"]
date: "2024-06-27"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Format Single-Line Property Promotion Constructors with Pint

> Configure Laravel Pint to collapse empty constructor bodies with promoted properties onto a single line for compact PHP 8 class declarations.

PHP 8 constructor property promotion eliminates explicit property declarations and assignments. However, empty constructor body braces `{}` can still take up 3 vertical lines.

Laravel Pint formats empty promoted constructors into clean single-line declarations:

```diff
class DatabaseNotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

-   public function __construct(public DatabaseNotification $notification)
-   {
-   }
+   public function __construct(public DatabaseNotification $notification) {}
}
```

- Compacts class definitions without losing readability
- Enforced automatically across your project via `./vendor/bin/pint`
- Keeps event, job, and DTO constructor signatures minimal
