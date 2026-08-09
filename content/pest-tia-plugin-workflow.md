---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX"]
date: "2026-05-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Streamline Testing with Pest Test Impact Analysis (TIA)

> Enable Test Impact Analysis (TIA) in Pest to only run tests directly covering code that changed since the last commit.

Running full test suites on every tiny change slows down feedback loops. Pest's TIA analyzes coverage artifacts to determine which tests cover modified lines.

```bash
./vendor/bin/pest --tia
```

- Only runs tests affected by recent code changes
- Can be configured in pest.php or run via --tia flag
- Slashes test suite execution time during development
