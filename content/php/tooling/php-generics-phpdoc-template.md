---
category: "PHP"
tags: ["PHP", "Generics", "PHPStan", "Type Safety", "Architecture"]
date: "2024-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Generic Classes and Functions in PHP Using PHPDoc @template

> Implement type-safe generic collections, wrapper classes, and utility functions in PHP using PHPDoc template annotations for static analysis engines.

While PHP does not have native language-level generic syntax (like `class Collection<T>`), static analysis tools such as PHPStan and Psalm provide full generic support via `@template` annotations.

By annotating wrapper classes and helper functions with `@template`, you preserve exact object types across transformations and collections without resorting to ambiguous `mixed` or `object` returns.

---

## 1. Generic Collections and Repositories

Annotate a class with `@template T` so the items stored inside match the items returned:

```php
/**
 * @template T
 */
class Collection
{
    /** @var list<T> */
    private array $items = [];

    /**
     * @param T $item
     */
    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return list<T>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return T|null
     */
    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }
}
```

### Usage with Type Preservation

```php
/** @var Collection<User> $userCollection */
$userCollection = new Collection();
$userCollection->add(new User('Alice'));

// IDE and PHPStan know $user is an instance of User
$user = $userCollection->first();
$userName = $user?->name;
```

---

## 2. Generic Type Constraints (`@template T of BaseClass`)

You can restrict a generic type parameter to a specific class, interface, or union type using the `of` keyword:

```php
abstract class Animal
{
    abstract public function speak(): string;
}

class Dog extends Animal
{
    public function speak(): string { return 'Woof'; }
}

class Cat extends Animal
{
    public function speak(): string { return 'Meow'; }
}

/**
 * @template T of Animal
 */
class Shelter
{
    /** @var list<T> */
    private array $residents = [];

    /**
     * @param T $animal
     */
    public function admit(Animal $animal): void
    {
        $this->residents[] = $animal;
    }

    /**
     * @return list<T>
     */
    public function getResidents(): array
    {
        return $this->residents;
    }
}
```

---

## 3. Generic Functions and Utility Methods

Generic functions capture the type passed in arguments and return that exact same type:

```php
/**
 * @template T
 * @param non-empty-array<T> $items
 * @return T
 */
function getFirstItem(array $items): mixed
{
    return $items[array_key_first($items)];
}

// Inferred return type: int
$number = getFirstItem([10, 20, 30]);

// Inferred return type: Product
$product = getFirstItem([new Product('Keyboard'), new Product('Mouse')]);
```

---

## 4. Generic Factory Methods with `class-string<T>`

When instantiating or resolving classes dynamically by class name, combine `@template T` with `class-string<T>`:

```php
/**
 * @template T of object
 * @param class-string<T> $className
 * @return T
 */
function createInstance(string $className): object
{
    return new $className();
}

// $service is statically typed as PaymentGateway, with full autocomplete
$service = createInstance(PaymentGateway::class);
```

---

## Summary

- Use `@template T` on classes and functions to capture dynamic types and prevent type degradation to `mixed`.
- Use `@template T of Constraint` to enforce class hierarchy or interface boundaries on generic parameters.
- Combine `@template T` with `class-string<T>` for strongly typed factory and dependency injection methods.
- Enables compile-time type safety across complex collections and architectures.
