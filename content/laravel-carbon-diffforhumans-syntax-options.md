---
category: "Laravel"
tags: ["Laravel", "Carbon", "Helpers"]
date: "2026-05-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Customize Relative Timestamps with diffForHumans() Options

> Pass Carbon options to diffForHumans() to control syntax flags, short units, and multi-part granularity.

Carbon's diffForHumans() displays human-readable dates. You can customize output by passing syntax flags for short units, removing 'ago' suffixes, or showing multiple time parts.

```php
use Illuminate\Support\Carbon;

$date = now()->subDays(3)->subHours(4);

// Default: '3 days ago'
echo $date->diffForHumans();

// Short units: '3d 4h ago'
echo $date->diffForHumans(['short' => true, 'parts' => 2]);

// Absolute (no 'ago'): '3 days'
echo $date->diffForHumans(['syntax' => Carbon::DIFF_ABSOLUTE]);
```

- short option abbreviates time units ('3d 4h')
- parts parameter displays precise multi-unit granularity
- DIFF_ABSOLUTE syntax removes directional 'ago' or 'from now' suffixes
