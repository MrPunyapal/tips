---
category: "PHP"
tags: ["PHP", "PHPStan", "Tooling", "Static Analysis", "Type Safety"]
date: "2024-07-25"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Complete Guide to PHPStan Static Analysis Levels (0 to 10)

> Understand what each rule level in PHPStan inspects, from basic syntax errors at Level 0 to strict mixed-type enforcement at Level 9 and Level 10.

PHPStan inspects your PHP code without executing it, discovering subtle type mismatches, dead code, and unhandled edge cases. PHPStan organizes its rule sets into numeric levels ranging from **0 (most lenient)** to **10 (maximum strictness)**.

When adopting PHPStan in existing or greenfield projects, understanding what each level verifies helps you establish progressive static analysis milestones.

## Overview of PHPStan Levels

| Level | Focus Area | What It Detects |
|---|---|---|
| **0** | Basic Checks | Unknown classes, non-existent methods, wrong argument counts |
| **1** | Undefined Variables | Variables that might be undefined in certain code branches |
| **2** | Unknown Methods & Properties | Validates methods/properties on all expressions and PHPDoc types |
| **3** | Return & Property Types | Ensures return types and property assignments match declared types |
| **4** | Dead Code Checks | Unreachable `else` branches, redundant `instanceof` checks |
| **5** | Function & Method Arguments | Verifies argument types passed to methods against signatures |
| **6** | Missing Typehints | Reports missing parameter and return type declarations |
| **7** | Union Type Accuracy | Detects partially wrong union types (calling a method only on one variant) |
| **8** | Nullable Type Safety | Flags method calls and property accesses on potentially null values |
| **9** | Strict `mixed` Evaluation | Prohibits calling methods or accessing properties on `mixed` without narrowing |
| **10** | Strict Explicit `mixed` Types | Prohibits passing or returning `mixed` where specific types are expected |

---

## Level-by-Level Code Examples

### Level 0: Basic Syntax & Function Calls
Verifies that called classes, functions, and methods exist, and that required parameter counts are satisfied:

```php
class Example
{
    public function run(): void
    {
        $this->unknownMethod(); // Error: Call to an undefined method
    }
}

function calculate(int $a, int $b): int
{
    return $a + $b;
}

calculate(1); // Error: Function calculate() called with 1 parameter, 2 required.
```

### Level 1: Possibly Undefined Variables
Ensures every variable accessed is guaranteed to be defined in all branching paths:

```php
function process(bool $flag): void
{
    if ($flag) {
        $data = 'ready';
    }

    echo $data; // Error: Variable $data might not be defined.
}
```

### Level 2: Unknown Methods on All Expressions
Checks methods and properties on expressions where types are known via annotations or reflection:

```php
class User
{
    public function getName(): string { return 'Alex'; }
}

$user = new User();
$user->getProfilePhoto(); // Error: Call to an undefined method User::getProfilePhoto()
```

### Level 3: Return Types and Property Assignments
Validates that returned values and assigned properties match their type definitions:

```php
class Order
{
    private int $total;

    public function setTotal(string $amount): void
    {
        $this->total = $amount; // Error: Property Order::$total (int) does not accept string.
    }

    public function getInvoiceNumber(): string
    {
        return 12345; // Error: Method getInvoiceNumber() should return string but returns int.
    }
}
```

### Level 4: Dead Code and Redundant Logic
Detects unreachable statements and conditionals that always evaluate to true or false:

```php
function check(User $user): void
{
    if ($user instanceof User) { // Always true
        echo 'Valid';
    }

    return;
    echo 'Unreachable'; // Error: Unreachable statement - code above always returns.
}
```

### Level 5: Argument Type Validation
Verifies that arguments passed to functions match the declared parameter types:

```php
function setDiscount(int $percentage): void {}

setDiscount('20'); // Error: Parameter #1 expects int, string given.
```

### Level 6: Missing Typehints
Requires explicit typehints on function parameters, return types, and properties:

```php
class Report
{
    // Error: Property Report::$records has no type specified.
    private $records;

    // Error: Method generate() has parameter $options with no type specified.
    public function generate($options) {}
}
```

### Level 7: Partially Wrong Union Types
Flags method calls on union types where only some union members implement the method:

```php
class Customer { public function sendInvoice(): void {} }
class Guest {}

function notify(Customer|Guest $recipient): void
{
    // Error: Call to method sendInvoice() on Customer|Guest (Guest does not have method).
    $recipient->sendInvoice();
}
```

### Level 8: Nullable Type Access
Catches potential "Call to a member function on null" errors:

```php
function printLength(?string $input): int
{
    // Error: Cannot call method strlen() with parameter ?string (requires string).
    return strlen($input);
}
```

### Level 9: Strict `mixed` Evaluation
At Level 9, `mixed` is treated strictly. You cannot perform operations on `mixed` values without explicit type narrowing:

```php
function formatData(mixed $value): string
{
    // Error: Cannot call method trim() on mixed. Narrow type with is_string() first.
    return trim($value);
}
```

### Level 10: Strict Explicit `mixed` Types (PHPStan 2.0+)
PHPStan 2.0 introduces Level 10 (or `level: max`), which goes beyond Level 9 by prohibiting passing `mixed` values or returning `mixed` where specific types are expected, ensuring full end-to-end type safety throughout generic containers and arrays:

```php
/**
 * @param array<string, mixed> $payload
 */
function processPayload(array $payload): string
{
    // Level 10 Error: Parameter #1 $name of function greet() expects string, mixed given.
    return greet($payload['name']);
}

function greet(string $name): string
{
    return "Hello, {$name}!";
}
```

To satisfy Level 10, explicitly assert or validate the type:

```php
function processPayload(array $payload): string
{
    if (! isset($payload['name']) || ! is_string($payload['name'])) {
        throw new InvalidArgumentException('Expected name to be a string.');
    }

    return greet($payload['name']); // Valid at Level 10
}
```

## Recommended Adoption Strategy

1. **Start at Level 1 or 2**: Fix baseline undefined variables and non-existent methods in legacy codebases.
2. **Advance to Level 5**: Add argument type verification to prevent runtime TypeError exceptions.
3. **Target Level 8 or Level 10**: Ideal for modern, strongly typed Laravel and PHP applications using Larastan.

## Summary

- PHPStan levels range progressively from 0 (lenient) to 10 (maximum strictness).
- Levels 1 to 5 catch common runtime bugs without requiring exhaustive PHPDoc annotations.
- Levels 6 to 8 enforce complete type coverage and nullable safety.
- Levels 9 and 10 enforce strict type narrowing on `mixed` values and array payloads.
