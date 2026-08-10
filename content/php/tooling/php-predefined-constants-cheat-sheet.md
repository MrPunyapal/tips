---
category: "PHP"
tags: ["PHP","Constants","Reference"]
date: "2025-06-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Master Essential PHP Predefined and Magic Constants

> A practical cheat sheet covering PHP magic constants, filesystem path helpers, error reporting masks, and CLI stream handles.

PHP provides built-in magic and environment constants that provide system context and platform independent path formatting.

```php
// Filesystem & Path Constants
DIRECTORY_SEPARATOR; // '/' on Unix, '\' on Windows
PATH_SEPARATOR;      // ':' on Unix, ';' on Windows
__FILE__;            // Full file path of current file
__DIR__;             // Directory of current file
__LINE__;            // Current line number

// Magic Compile Constants
__FUNCTION__;        // Current function name
__CLASS__;           // Current class name
__TRAIT__;           // Current trait name
__METHOD__;          // Current class method name
__NAMESPACE__;       // Current namespace name

// Environment & Platform
PHP_VERSION;         // e.g. "8.4.1"
PHP_OS_FAMILY;       // "Windows", "Linux", "BSD", "Darwin"
PHP_EOL;             // End-of-line character for host OS
PHP_INT_MAX;         // Maximum integer supported

// CLI Stream Handles (available in CLI mode)
STDIN;               // Standard input stream
STDOUT;              // Standard output stream
STDERR;              // Standard error stream
```

- Always use `DIRECTORY_SEPARATOR` or forward slashes for cross-platform file paths
- Use `PHP_OS_FAMILY` instead of checking `PHP_OS` strings
- Magic constants resolve at compile time, making sure minimal runtime overhead
