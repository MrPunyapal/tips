---
category: "Laravel"
tags: ["Laravel", "Testing", "Events", "Clean Code"]
date: "2026-02-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Fake Events Selectively with Event::fakeExcept()

> Use Event::fakeExcept() to mock all application events while allowing specific mission-critical event listeners to run during tests.

Calling `Event::fake()` mutes all event listeners in the application. When a feature test depends on a specific internal listener executing (such as a listener that generates an API token or calculates user stats) while mocking outbound email events, standard faking breaks the test setup.

`Event::fakeExcept()` allows designated events to execute normally.

## Selective Event Faking in Tests

```php
use App\Events\OrderPlaced;
use App\Events\SendWelcomeEmail;
use Illuminate\Support\Facades\Event;

test('order placement generates invoice but fakes email notifications', function () {
    // Fake all events EXCEPT OrderPlaced (which generates invoice records)
    Event::fakeExcept([
        OrderPlaced::class,
    ]);

    // OrderPlaced fires and executes its real database listener
    $order = OrderService::placeOrder($payload);

    $this->assertDatabaseHas('invoices', ['order_id' => $order->id]);

    // External email event was intercepted and faked
    Event::assertDispatched(SendWelcomeEmail::class);
});
```

## Summary

- Mutes all events across the application except explicitly whitelisted classes.
- Enables testing internal state listeners without triggering external side effects.
- Clean alternative to complex manual listener mocking.
