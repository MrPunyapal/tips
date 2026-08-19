---
category: "PHP"
tags: ["PHP", "DocSmith", "Documentation", "Tooling", "Open Source", "Markdown"]
date: "2026-08-19"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Build Polished Static Documentation Sites from Markdown with DocSmith

> DocSmith is a lightweight PHP tool that compiles Markdown directories into responsive, searchable, SEO-friendly static documentation websites with zero frontend configuration.

Writing technical documentation in Markdown is straightforward, but transforming those files into a searchable, responsive website often introduces complex JavaScript static site generators, node build pipelines, or heavy CMS platforms.

`mrpunyapal/docsmith` is a native PHP library and standalone CLI tool that compiles a directory of Markdown files directly into clean static HTML pages with built-in dark mode, dual-theme syntax highlighting, search, and AI-ready exports.

You can explore a live example of a site generated with DocSmith at [mrpunyapal.github.io/docsmith](https://mrpunyapal.github.io/docsmith/).

## How DocSmith Works

DocSmith follows a straightforward static compilation pipeline:

```text
Markdown Files (docs/)
          ↓
DocSmith Engine
├── Frontmatter parsing and document discovery
├── League CommonMark (GFM) + Phiki syntax highlighting
├── Navigation tree generation and custom ordering
└── Search index, sitemap, and LLM text generation
          ↓
Static HTML Documentation Site (dist/)
```

1. **Source Discovery**: Scans your documentation directory and parses YAML frontmatter metadata.
2. **Markdown Rendering**: Converts Markdown through League CommonMark with GitHub-flavored Markdown extensions.
3. **Syntax Highlighting**: Renders server-side code syntax highlighting using Phiki with GitHub Light and Dark themes.
4. **Site Assembly**: Injects navigation, table of contents, search overlays, and responsive mobile drawers.
5. **Asset & Metadata Publishing**: Emits static assets, `search-index.json`, `sitemap.xml`, `llms.txt`, and `.nojekyll`.

## Installation and Quick Start

DocSmith requires **PHP 8.3 or newer** and Composer:

```bash
composer require --dev mrpunyapal/docsmith
```

### 1. Build via CLI

DocSmith ships with a standalone binary at `vendor/bin/docsmith`:

```bash
vendor/bin/docsmith build --source=md --output=docs --title="Package Docs"
```

### 2. Build via PHP API

You can also run builds directly within PHP build scripts using the static entry point or fluent builder:

```php
use Docsmith\Docsmith;

Docsmith::make()
    ->source(__DIR__ . '/md')
    ->output(__DIR__ . '/dist')
    ->title('Acme Cache Docs')
    ->description('High-performance caching library for PHP.')
    ->accentColor('#ff2d20')
    ->accentColorDark('#ff6b61')
    ->siteUrl('https://acme.github.io/cache')
    ->repositoryUrl('https://github.com/acme/cache')
    ->rightSidebar()
    ->build();
```

## Organizing Markdown Files

DocSmith maps your Markdown files directly to clean static URLs:

```text
md/
├── index.md                    →  docs/index.html
├── installation.md             →  docs/installation/index.html
├── configuration.md            →  docs/configuration/index.html
└── usage/
    ├── basics.md               →  docs/usage/basics/index.html
    └── advanced.md             →  docs/usage/advanced/index.html
```

If your source directory does not contain an `index.md`, DocSmith automatically generates a clean landing page listing all available documentation sections.

### Frontmatter Options

Each Markdown file can include YAML frontmatter to control page metadata and navigation:

```markdown
---
title: Advanced Configuration
description: Fine-tune caching drivers, timeouts, and memory limits.
sidebar_label: Configuration
order: 3
hidden: false
---

# Advanced Configuration
```

- `title`: Page title displayed in browser tabs and navigation.
- `description`: Meta description used for SEO and search snippets.
- `sidebar_label`: Shorter label displayed in the sidebar menu.
- `order`: Numeric sort priority.
- `hidden`: When set to `true`, the page is rendered to HTML but omitted from the sidebar, search index, and previous/next pagination.

## Customizing Sidebar Navigation Order

By default, pages appear in alphabetical or frontmatter-defined order. You can set an explicit sequence using `navigationOrder()`:

```php
Docsmith::make()
    ->source(__DIR__ . '/md')
    ->output(__DIR__ . '/dist')
    ->navigationOrder([
        'Introduction',
        'Installation',
        'Configuration',
        'Basic Usage',
        'Advanced Usage',
    ])
    ->build();
```

Entries can match page titles, `sidebar_label` properties, or relative Markdown paths. Unlisted pages retain their natural position after ordered items.

## Polished Reading Experience

DocSmith generates standalone, zero-dependency HTML with built-in UX features:

### 1. Dual-Theme Syntax Highlighting
Code blocks are highlighted during static compilation using **Phiki**, emitting token styles for both light and dark backgrounds without client-side highlighting libraries:

```php
use Acme\Cache\Repository;

$cache = new Repository(driver: 'redis');
$cache->set('user:42', $user, ttl: 3600);
```

Each code block includes a 1-click clipboard copy button.

### 2. Light and Dark Theme Modes
The site includes a theme toggle that automatically detects system color scheme preferences (`prefers-color-scheme`) and persists manual user selections via `localStorage`.

### 3. Responsive Navigation & Tables
- Mobile layouts include a sliding navigation drawer with touch backdrop support.
- Wide Markdown tables are automatically wrapped in `<div class="table-scroll">` containers to prevent viewport overflow on smaller screens.
- An optional right sidebar (`->rightSidebar()`) extracts page headings into an active Table of Contents.

## Built-In Search & Modal Overlay

DocSmith builds a static `search-index.json` during compilation containing page titles, descriptions, section headings, and content text:

- **Sidebar Filter**: Instantly filters sidebar navigation links as you type.
- **Global Search Overlay**: Press `Cmd+K` (macOS) or `Ctrl+K` (Windows/Linux) or click the search bar to launch a modal search dialog with full content matching.

## Multi-Version Documentation

For libraries maintaining multiple major releases, DocSmith provides multi-version builds with a header version switcher:

```php
Docsmith::make()
    ->source(__DIR__ . '/md')
    ->output(__DIR__ . '/dist')
    ->versions([
        'v1' => ['label' => 'v1.x', 'source' => __DIR__ . '/md/v1', 'default' => true],
        'v2' => ['label' => 'v2.x', 'source' => __DIR__ . '/md/v2'],
    ])
    ->build();
```

- The default version outputs directly to the root (`/installation/index.html`).
- Secondary versions output to versioned subpaths (`/v2/installation/index.html`).
- A version switcher dropdown is automatically rendered in the header navigation.

## AI-Ready Documentation Exports

DocSmith automatically exports documentation in formats structured for LLMs, coding agents, and automated workflows:

| File | Format and Purpose |
|---|---|
| `llms.txt` | Standard directory index per the [llms.txt](https://llmstxt.org/) specification with absolute URLs and summaries. |
| `llms-full.txt` | Concatenated plain-text export of the entire documentation site. |
| `export/docs.md` | Single unified Markdown file containing all pages and metadata. |

AI exports are enabled by default and can be toggled using `->llmsExport(false)`.

## Open Graph Social Cards

DocSmith can generate Open Graph preview images using Playwright and capturist:

```php
Docsmith::make()
    ->source(__DIR__ . '/md')
    ->output(__DIR__ . '/docs')
    ->siteUrl('https://acme.github.io/cache')
    ->ogGeneratedAll() // Generates docs/og/cover.png
    ->build();
```

You can generate a single shared social cover or individual cards per page (`->ogGeneratedPerPage()`), with support for custom HTML card templates (`->ogTemplate(...)`).

## Real-World Example: Package Documentation Script

Here is a complete `build-docs.php` script for a PHP package:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Docsmith\Docsmith;

Docsmith::make()
    ->source(__DIR__ . '/docs')
    ->output(__DIR__ . '/dist')
    ->title('AuditLogger PHP')
    ->description('Immutable audit logging and security tracing for PHP applications.')
    ->accentColor('#0284c7')
    ->accentColorDark('#38bdf8')
    ->siteUrl('https://acme.github.io/audit-logger')
    ->repositoryUrl('https://github.com/acme/audit-logger')
    ->editBranch('main')
    ->rightSidebar()
    ->navigationOrder([
        'Getting Started',
        'Installation',
        'Configuration',
        'Logging Events',
        'Custom Formatters',
    ])
    ->build();

echo "Documentation built successfully in dist/\n";
```

Add a Composer shortcut to `composer.json`:

```json
"scripts": {
    "docs:build": "@php build-docs.php"
}
```

## Summary

DocSmith provides a focused, native PHP solution for turning Markdown into production-ready static documentation sites:

- **Zero Node Runtime**: Compiles complete HTML sites natively in PHP without requiring node modules or complex JavaScript build tools.
- **Built-in Essentials**: Includes dark mode, Phiki syntax highlighting, search index generation, mobile navigation, and LLM text exports out of the box.
- **Hosting Agnostic**: Generates clean static folders compatible with GitHub Pages (`.nojekyll`), Cloudflare Pages, Netlify, and Vercel.
- **Live Documentation**: View a live production example at [mrpunyapal.github.io/docsmith](https://mrpunyapal.github.io/docsmith/).
- **GitHub Repository**: [MrPunyapal/DocSmith](https://github.com/MrPunyapal/docsmith)
