---
category: "Laravel"
tags: ["Laravel", "Migrations", "DevOps"]
date: "2026-01-17"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Configuration"
---

# Squash Legacy Database Migrations Carefully

> Use php artisan schema:dump to collapse hundreds of old migration files into a single SQL schema file while preserving new migrations.

As applications age, running hundreds of individual database migration files slows down test suites and deployment setups. Use schema:dump to collapse old migrations into a clean schema file.

```bash
# Dump schema and prune old migration files
php artisan schema:dump --prune

# Schema is saved to database/schema/mysql-schema.sql
```

- Collapses old migration files into a single database/schema dump file
- Slashes migration execution time during automated test suite runs
- New migration files created after squashing run sequentially after the schema dump
