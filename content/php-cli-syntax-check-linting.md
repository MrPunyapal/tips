---
category: "PHP"
tags: ["PHP","CLI","Tooling"]
date: "2025-06-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Lint PHP Files Instantly with php -l CLI Syntax Check

> Run fast, zero-dependency PHP syntax validation from the terminal using php -l on individual files or recursively across entire projects.

Before running static analysis tools or unit test suites, you can instantly verify that your PHP files contain no syntax errors directly from the terminal.

The `-l` (lint) flag checks code syntax without executing the script:

```bash
# Check syntax of a single file
php -l app/Models/User.php
# Output: No syntax errors detected in app/Models/User.php

# Check all PHP files in project recursively (Linux/macOS)
find . -name "*.php" -exec php -l {} \;

# Check all PHP files recursively using PowerShell (Windows)
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

- Runs instantly with 0 external dependencies
- Catches parse errors, missing semicolons, and bracket mismatches early
- Easy addition to git pre-commit hooks and lightweight CI pipelines
