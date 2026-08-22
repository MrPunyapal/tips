---
category: "PHP"
tags: ["PHP", "Performance", "CLI", "Architecture"]
date: "2025-06-13"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Tick-Based Execution Timeout Wrapper in PHP Without PCNTL

> Interrupt long-running synchronous PHP callables on any operating system using PHP tick declarations and tick callback functions without requiring the pcntl extension.

In command-line scripts, background jobs, or plugin execution engines, you may need to impose a hard execution timeout on a closure or callable. The standard Unix approach uses `pcntl_alarm()` or `pcntl_fork()`, but the `pcntl` extension is unavailable on Windows environments and in certain shared hosting setups.

Using PHP ticks (`declare(ticks=1)`), you can implement a cross-platform execution watchdog that monitors elapsed execution time and interrupts runaway code.

## The TimeoutGuard Implementation

```php
declare(ticks=1);

class TimeoutException extends Exception {}

class TimeoutGuard
{
    public static function run(callable $callback, float $seconds): mixed
    {
        $start = microtime(true);

        // Tick handler: checks elapsed time on every low-level tick event
        $watchdog = function () use ($start, $seconds): void {
            if ((microtime(true) - $start) > $seconds) {
                throw new TimeoutException("Execution interrupted: timeout of {$seconds}s exceeded");
            }
        };

        register_tick_function($watchdog);

        try {
            return $callback();
        } finally {
            unregister_tick_function($watchdog);
        }
    }
}
```

## Usage Example

Wrap any synchronous task inside `TimeoutGuard::run()`:

```php
try {
    $result = TimeoutGuard::run(function () {
        // Heavy computational task or complex regex matching
        for ($i = 0; $i < 1e7; $i++) {
            sqrt($i);
        }
        return 'Completed';
    }, 0.5); // 500ms limit

    echo "Task finished: {$result}
";
} catch (TimeoutException $e) {
    echo "Execution timed out: " . $e->getMessage() . "
";
}
```

## How It Works

1. **`declare(ticks=1)`**: Tells the PHP parser to emit a tick event after every single low-level statement execution.
2. **`register_tick_function()`**: Registers a lightweight watchdog callback evaluated on each tick.
3. **`microtime(true)` Check**: Compares the current timestamp against the initial start time. If the elapsed time exceeds the threshold, it throws a `TimeoutException`.
4. **`finally` Cleanup**: Always unregisters the tick handler when execution finishes (or errors) to avoid overhead in subsequent operations.

## When to Use This Pattern

- **User-submitted Script Sandboxing**: Enforcing computation budgets on dynamic expressions or formula evaluation.
- **Cross-Platform CLI Tools**: Running timeout-protected commands on both Windows and Linux without conditional `pcntl` checks.
- **Complex Regex Matching**: Preventing catastrophic backtracking (ReDoS) from hanging PHP worker processes.

## Summary

- Use `declare(ticks=1)` and `register_tick_function()` to create a zero-dependency execution timeout watchdog.
- Works across all operating systems including Windows where `pcntl` is unavailable.
- Always unregister tick callbacks inside a `finally` block to prevent memory leaks and unwanted background invocation.
