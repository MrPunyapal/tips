---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX"]
date: "2025-12-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Testing"
---

# Enable Compact Test Output Printer in Pest

> Use the --compact flag or configure compact output in pest.php for minimal single-character test progress indicators.

When running large test suites with hundreds of tests, verbose output fills terminal buffers. Enabling compact printer output displays dots and characters for fast console feedback.

```bash
# Enable compact output via CLI
./vendor/bin/pest --compact
```

- Displays minimal test progress indicators to reduce console output clutter
- Highlights failures and errors prominently with detailed tracebacks
- Saves terminal scrollback memory during long test suite runs
