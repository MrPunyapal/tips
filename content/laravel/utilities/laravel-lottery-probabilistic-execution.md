---
category: "Laravel"
tags: ["Laravel", "Architecture", "Performance", "Utilities"]
date: "2023-02-15"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Execute Tasks Probabilistically with the Lottery Class

> Use the Lottery class to run sample logging, performance profiling, and background metrics on a fraction of incoming requests.

Running expensive diagnostic operations (such as query profiling, sample telemetry, or cache warming) on every single HTTP request degrades performance.

Laravel provides the `Lottery` class to execute callbacks probabilistically.

## Running Code Based on Odds

```php
use Illuminate\Support\Lottery;

// Executes the closure in 1 out of every 100 requests (1% sample rate)
Lottery::odds(1, 100)
    ->winner(function () {
        logger()->info('Sampled request profiling triggered.', [
            'memory' => memory_get_peak_usage(true),
        ]);
    })
    ->choose();
```

## Handling the Loser Callback

Define an optional `->loser()` callback for the remaining requests:

```php
$driver = Lottery::odds(1, 10)
    ->winner(fn () => 'experimental_v2')
    ->loser(fn () => 'stable_v1')
    ->choose();
```

## Testing Probabilities

In test suites, force winners or losers deterministically:

```php
// Always win in tests
Lottery::alwaysWin();

// Always lose in tests
Lottery::alwaysLose();
```

## Summary

- Executes sample operations on a fractional percentage of requests.
- Accepts ratio definitions via `Lottery::odds($wins, $outOf)`.
- Features `alwaysWin()` and `alwaysLose()` for deterministic testing.
