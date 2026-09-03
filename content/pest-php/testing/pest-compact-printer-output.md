---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX"]
date: "2025-12-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Enable Compact Test Output Printer in Pest

> Use the --compact flag or set it in your composer.json test script for minimal single-character test progress indicators.

When running large test suites with hundreds of test cases, verbose multi-line output fills your terminal scrollback buffer.

Pest provides a compact output mode that displays concise single-character indicators for fast visual feedback.

---

## 1. Run on Demand via CLI

```bash
./vendor/bin/pest --compact
```

---

## 2. Set as Default in composer.json

To make compact output the default for your team and CI runs, configure it inside `composer.json`:

```json
{
    "scripts": {
        "test": "pest --compact"
    }
}
```

Now running `composer test` executes Pest in compact mode automatically.

---

## Key Benefits

- **Minimal Terminal Clutter**: Renders small single-character status indicators (`.` for passed, `F` for failed) instead of multi-line test names.
- **Immediate Failure Details**: Test failures, assertion errors, and stack traces are still rendered prominently at the end of the run.
- **Scrollback Efficiency**: Keeps terminal buffers clean during extensive test suite runs.
