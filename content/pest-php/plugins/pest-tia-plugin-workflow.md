---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX"]
date: "2026-05-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Plugins"
---

# Streamline Testing with Pest Test Impact Analysis (TIA)

> Enable the Tia Engine in Pest to only re-run tests affected by your latest code changes while replaying cached results for unaffected tests.

Running full test suites on every minor change slows down local feedback loops. While `--dirty` only checks if test files themselves changed, Pest's Tia Engine analyzes code coverage mapping to identify tests that execute the actual application lines you modified.

---

## 1. CLI Usage

Run Pest with the `--tia` flag:

```bash
# Run tests with Test Impact Analysis enabled
./vendor/bin/pest --parallel --tia
```

On the initial run, Pest records a dependency graph of which tests touch which files. On subsequent runs, it only re-runs affected tests and replays cached results for the rest.

---

## 2. Configuration in tests/Pest.php

To avoid typing `--tia` on every run, configure the Tia Engine fluently inside `tests/Pest.php`:

```php
// tests/Pest.php

pest()->tia()
    ->locally()   // Run TIA on every local invocation; skipped automatically in CI
    ->baselined() // Fetch shared baseline from CI when no local graph exists
    ->filtered(); // Narrow test runner to affected test files only
```

- **`locally()`**: Recommended. Activates TIA for every local `pest` run without requiring the `--tia` flag. In CI (or with `--ci`), TIA is skipped automatically so pipelines run the full suite.
- **`always()`**: Alternative to `locally()`. Enforces TIA across all environments including CI.
- **`baselined()`**: Downloads the shared CI baseline artifact so developers replay immediately on fresh clones.

---

## Key Rules & Requirements

- **Coverage Driver**: Requires a code coverage driver (**PCOV** or **Xdebug**) to build the dependency graph.
- **Cosmetic Invariant**: Whitespace changes, comments, and docblock edits produce identical hashes, executing zero tests.
- **CLI Overrides**: Bypass or force TIA anytime using `--tia` or `--no-tia`.
