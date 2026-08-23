---
category: "PHP"
tags: ["PHP", "PHPStan", "Type Safety", "Arrays", "Tooling"]
date: "2024-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Typed Arrays and Array Shapes in PHP with PHPDoc

> Document and enforce structured array schemas using PHPDoc array shapes, lists, and non-empty array annotations for static analysis in PHPStan and Psalm.

PHP arrays are versatile: they function as zero-indexed lists, associative maps, and nested dictionary structures. However, using native typehints like `function process(array $data)` leaves the contents of the array untyped, leading to potential missing key errors or unexpected values at runtime.

By using PHPDoc array shapes and pseudo-types, static analysis tools (like PHPStan and Psalm) and modern IDEs (such as PhpStorm and VS Code) can strictly validate array keys and types without requiring dedicated Data Transfer Objects (DTOs) for every small payload.

---

## 1. Defining Exact Array Shapes (`array{...}`)

Array shapes define the exact dictionary schema of an associative array, including expected keys and their individual types:

```php
/**
 * @param array{id: int, name: string, email: string, is_active?: bool} $user
 * @return array{status: string, timestamp: int}
 */
function registerUser(array $user): array
{
    // PHPStan ensures $user['id'], $user['name'], and $user['email'] exist
    $status = $user['is_active'] ?? true ? 'active' : 'pending';

    return [
        'status' => $status,
        'timestamp' => time(),
    ];
}
```

- **Required Keys**: `id: int, name: string`
- **Optional Keys (`?`)**: `is_active?: bool` (the key may or may not be present in the array)

---

## 2. Zero-Indexed Lists (`list<T>`)

Standard PHP arrays can have arbitrary or sparse integer keys. When an array is guaranteed to be a contiguous, zero-indexed numerical list (`0, 1, 2, ...`), use `list<T>`:

```php
/**
 * @param list<int> $scores
 * @return float
 */
function calculateAverage(array $scores): float
{
    if ($scores === []) {
        return 0.0;
    }

    return array_sum($scores) / count($scores);
}

// Valid list
calculateAverage([90, 85, 95]);

// PHPStan flags non-list associative arrays
calculateAverage(['first' => 90, 'second' => 85]); // Error
```

---

## 3. Associative Key-Value Maps (`array<K, V>`)

When working with dynamic key-value maps (such as configuration options or lookup tables):

```php
/**
 * @param array<string, mixed> $payload
 * @return string
 */
function serializePayload(array $payload): string
{
    return json_encode($payload, JSON_THROW_ON_ERROR);
}

/**
 * @param array<int, User> $usersById
 */
function notifyUsers(array $usersById): void
{
    foreach ($usersById as $id => $user) {
        // $id is typed as int, $user is typed as User
        $user->sendNotification();
    }
}
```

---

## 4. Non-Empty Arrays (`non-empty-array<T>`)

If a function requires at least one element to prevent division-by-zero or empty-set errors, declare it with `non-empty-array`:

```php
/**
 * @param non-empty-array<string> $recipients
 * @return string
 */
function getPrimaryRecipient(array $recipients): string
{
    // Guaranteed to contain at least 1 element; safe from undefined offset
    return $recipients[0];
}
```

---

## 5. Typed Callback Arrays (`array<callable>`)

When storing or dispatching collections of closures or invokable handlers:

```php
/**
 * @param list<callable(): void> $listeners
 */
function triggerListeners(array $listeners): void
{
    foreach ($listeners as $listener) {
        $listener();
    }
}

triggerListeners([
    fn () => logger()->info('First listener executed'),
    fn () => logger()->info('Second listener executed'),
]);
```

---

## Summary

- Use `array{key: type}` to define explicit key requirements and prevent missing array index bugs.
- Use `list<T>` for contiguous, zero-indexed sequential lists.
- Use `array<KeyType, ValueType>` for generic maps and associative containers.
- Use `non-empty-array<T>` to statically assert that an array contains at least one item.
