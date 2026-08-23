---
category: "PHP"
tags: ["PHP", "Types", "Syntax", "Reference", "Type Safety"]
date: "2024-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Syntax"
---

# Complete Modern PHP Type System Cheat Sheet

> A reference guide to scalar, compound, special, user-defined, union, and intersection types in modern PHP.

PHP features a gradual and expressive type system. From scalar type declarations introduced in PHP 7 to union types in PHP 8.0, intersection types in PHP 8.1, and standalone `true`, `false`, and `null` types in PHP 8.2, PHP provides robust type safety at runtime and compile time.

---

## 1. Atomic and Scalar Types

Scalar types represent basic primitive values:

```php
// Scalar Types
$active   = true;              // bool (or literal true / false)
$quantity = 42;                // int
$price    = 19.99;             // float
$name     = 'Punyapal';        // string

// Built-in Special Types
$empty    = null;              // null
$items    = [1, 2, 3];         // array
$payload  = new stdClass();    // object
$handle   = fopen('php://memory', 'r+'); // resource
```

---

## 2. Special Return Types

PHP supports specialized return types that describe function termination and inheritance contexts:

```php
// void: Function executes without returning a value
function logMessage(string $msg): void
{
    echo $msg;
}

// never: Function always exits or throws an exception, never finishing normally
function abortWithError(string $error): never
{
    throw new RuntimeException($error);
}

// Relative Class Types
class Model
{
    // self: Returns an instance of the exact class where the method is defined
    public function createSelf(): self
    {
        return new self();
    }

    // static: Returns an instance of the calling class (respects Late Static Binding)
    public static function make(): static
    {
        return new static();
    }
}

class User extends Model
{
    // parent: References the immediate parent class
    public function getBaseModel(): parent
    {
        return new parent();
    }
}
```

---

## 3. User-Defined Types

Types created within application domain code:

```php
// Interfaces
interface Notifiable
{
    public function notify(): void;
}

// Concrete Classes
class Invoice implements Notifiable
{
    public function notify(): void {}
}

// Backed & Pure Enums (PHP 8.1+)
enum OrderStatus: string
{
    case Draft = 'draft';
    case Paid  = 'paid';
}
```

---

## 4. Callables and Invocations

```php
// Closure or invokable class instance
function executeTask(callable $task): void
{
    $task();
}

executeTask(fn () => print("Task completed\n"));
```

---

## 5. Composite Types: Unions and Intersections

### Union Types (`A|B`)
Allows an argument or return value to satisfy any of the listed types:

```php
function findUser(int|string $identifier): ?User
{
    return is_int($identifier)
        ? User::find($identifier)
        : User::where('email', $identifier)->first();
}
```

### Intersection Types (`A&B`)
Requires an object to satisfy all listed interfaces simultaneously:

```php
interface HasId { public function getId(): int; }
interface HasName { public function getName(): string; }

function displayEntity(HasId&HasName $entity): string
{
    return "#{$entity->getId()}: {$entity->getName()}";
}
```

### Disjunctive Normal Form (DNF) Types (PHP 8.2+)
Combines Union and Intersection types using parentheses:

```php
// Accepts (HasId AND HasName) OR null
function formatRecord((HasId&HasName)|null $record): string
{
    return $record ? displayEntity($record) : 'Anonymous';
}
```

---

## 6. Top Types: `mixed` and `iterable`

```php
// mixed: Equivalent to array|bool|callable|int|float|null|object|resource|string
function debugValue(mixed $value): void
{
    var_dump($value);
}

// iterable: Accepts both arrays and objects implementing Traversable (like Generators or Collections)
function processBatch(iterable $items): void
{
    foreach ($items as $item) {
        // ...
    }
}
```

---

## Summary

- Use scalar and atomic types (`int`, `string`, `bool`, `float`) for basic primitives.
- Use `void` when no value is returned, and `never` for methods that always terminate or throw.
- Use `static` return types for fluent chaining and late static binding in parent classes.
- Use Union types (`int|string`) and Intersection types (`InterfaceA&InterfaceB`) for precise polymorphic contracts.
- Use `iterable` to accept both arrays and `Traversable` collections seamlessly.
