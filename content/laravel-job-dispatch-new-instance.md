---
category: "Laravel"
tags: ["Laravel", "Queue", "Type Safety"]
date: "2024-12-23"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Prefer dispatch(new Job(...)) Over Static Job::dispatch()

> Use dispatch(new ProcessReport($data)) for superior IDE constructor auto-completion and static analysis type checking.

Static Job::dispatch(...) relies on magical __callStatic methods which can bypass IDE parameter type checking. Instantiating job classes directly via dispatch(new Job(...)) guarantees strict type checking.

```php
use App\Jobs\ProcessReport;

// ❌ Static magic method: weak IDE constructor type completion
// ProcessReport::dispatch($reportId);

// ✅ Explicit instantiation: full IDE auto-completion & static analysis
dispatch(new ProcessReport($reportId));
```

- Provides full static analysis parameter validation in PHPStan and Psalm
- Ensures IDE auto-completes constructor parameters accurately
- Avoids relying on magic __callStatic methods
