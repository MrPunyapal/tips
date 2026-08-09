---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX", "PHPStan"]
date: "2026-05-18"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Streamline Testing with Pest Test Impact Analysis (TIA)

> Enable Test Impact Analysis (TIA) in Pest 5 to only run tests directly covering code that changed since the last commit, slashing feedback cycles.

As your test suite grows, running the full suite on every tiny change slows down your development loop.

With Pest's **Test Impact Analysis (TIA)**, Pest analyzes code coverage artifacts to determine which tests cover the exact lines of code you modified.

### Run tests with TIA:

```bash
# Only run tests affected by git changes
./vendor/bin/pest --tia
```

### Enable by default in `pest.php`:

You can configure Pest to automatically run in TIA mode during active development or when watching files:

```php
// pest.php
uses()
    ->beforeEach(function () {
        // Shared test setup
    })
    ->in('Feature', 'Unit');
```

```bash
# Watch mode with TIA for instant feedback on file save
./vendor/bin/pest --watch --tia
```
