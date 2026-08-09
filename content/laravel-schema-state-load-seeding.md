---
category: "Laravel"
tags: ["Laravel", "Database", "Testing"]
date: "2026-01-07"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Seed Large Database Dumps with SchemaState::load()

> Use SchemaState::load() to load large raw SQL dump files directly through database CLI tools instead of slow DB::unprepared() execution.

Executing massive raw SQL dumps inside DB::unprepared() runs through PHP memory buffers and PDO statements, which is slow. SchemaState::load() invokes native database CLI tools (mysql/psql) directly.

```php
use Illuminate\Support\Facades\DB;

// Fast native CLI SQL dump loading
$connection = DB::connection();
$connection->getSchemaState()->load(database_path('dumps/initial_data.sql'));
```

- Uses native database CLI binaries (mysql/psql) for maximum execution speed
- Bypasses PHP memory buffer overhead during large data imports
- Ideal for seeding production-like reference datasets in test environments
