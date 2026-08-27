---
category: "Laravel"
tags: ["Laravel", "Architecture", "Controllers", "Design Patterns"]
date: "2024-07-10"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Keep Controllers Lean by Delegating Business Logic to Action Classes

> Refactor fat controller methods by moving multi-step transactions, file storage, and third-party integrations into invokable Action classes.

When a controller handles file uploads, payment transactions, database record creation, and email notifications in a single method, testing individual business steps becomes difficult and duplicates logic across CLI commands or API endpoints.

Moving domain logic into dedicated, invokable Action classes creates modular, reusable application layers.

## Fat Controller (Anti-Pattern)

```php
public function store(StoreOrderRequest $request)
{
    // ❌ Fat controller mixing validation, billing, file storage, and events
    $payment = Stripe::charges()->create([...]);
    $order = Order::create([...]);
    $invoicePdf = PDF::generate($order);
    Storage::disk('s3')->put("invoices/{$order->id}.pdf", $invoicePdf);
    Mail::to($request->user())->send(new OrderConfirmation($order));

    return to_route('orders.show', $order);
}
```

## Refactored Action Class

```php
namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function __construct(
        protected PaymentGateway $gateway,
        protected InvoiceGenerator $invoices
    ) {}

    public function execute(User $user, array $orderData, string $paymentToken): Order
    {
        return DB::transaction(function () use ($user, $orderData, $paymentToken) {
            $charge = $this->gateway->charge($user, $orderData['total'], $paymentToken);
            $order = $user->orders()->create([...$orderData, 'transaction_id' => $charge->id]);
            $this->invoices->generateAndStore($order);

            return $order;
        });
    }
}
```

## Skinny Controller

```php
public function store(StoreOrderRequest $request, CreateOrderAction $action)
{
    $order = $action->execute(
        $request->user(),
        $request->validated(),
        $request->input('payment_token')
    );

    return to_route('orders.show', $order)->with('success', 'Order created.');
}
```

## Summary

- Action classes isolate core business rules from HTTP transport details.
- Reusable across web controllers, API endpoints, queue workers, and Artisan commands.
- Easily testable with isolated unit test mocks.
