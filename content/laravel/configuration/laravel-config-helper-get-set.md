---
category: "Laravel"
tags: ["Laravel", "Configuration", "Helpers"]
date: "2024-08-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Configuration"
---

# Retrieve and Override Configurations with the config() Helper

> Use config('app.name') to retrieve settings and config(['app.debug' => true]) to set runtime overrides.

The config() helper accesses application configuration options defined in config/*.php files. You can also pass key-value arrays to override configurations dynamically during testing or request execution.

```php
// Retrieve config value with fallback default
$appName = config('app.name', 'Laravel');

// Override config value dynamically at runtime
config(['services.stripe.key' => 'pk_test_12345']);
```

- Dot-notation syntax accesses nested configuration array keys
- Passing key-value arrays overrides configurations dynamically for current request
- Always use config() instead of env() outside config directory files
