---
category: "Pest PHP"
tags: ["Pest", "Testing", "DX"]
date: "2026-07-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Enable Test Impact Analysis (TIA) by Default in pest.php

> Configure Pest to run Test Impact Analysis by default in pest.php so only tests covering modified files execute.

Running full test suites on every minor edit slows down development feedback. Configuring TIA in pest.php automatically detects file changes and runs only affected tests.

```php
// tests/Pest.php
uses()
    ->compact()
    ->in(__DIR__);

// Run only tests covering changed code automatically
// Terminal: ./vendor/bin/pest --tia
```

- Slashes test execution duration during rapid local iterations
- Tracks code coverage artifacts to identify tests covering edited lines
- Can be toggled via CLI flag --tia or environment config
