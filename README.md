# Laravel Tips

This repository contains the Markdown source files for **Laravel Tips** published at [mrpunyapal.dev/tips](https://mrpunyapal.dev/tips).

## Website

Published interactive version: [https://mrpunyapal.dev/tips](https://mrpunyapal.dev/tips)
RSS Feed: [https://mrpunyapal.dev/tips/feed.xml](https://mrpunyapal.dev/tips/feed.xml)

The website is statically generated directly from the Markdown files maintained in this repository.

## What You'll Find

Practical, bite-sized engineering tips covering Laravel, PHP, and the wider ecosystem. Topics include Eloquent, Livewire, Flux, Filament, testing with Pest PHP, queues, performance optimization, validation, APIs, and everyday developer workflows.

## Category & Subcategory Directory Index

Explore **329** engineering tips directly in the repository by category:

<!-- TIPS_INDEX:START -->
- **Laravel (284)**
  - [Architecture (9)](content/laravel/architecture)
  - [Blade (8)](content/laravel/blade)
  - [Cache (2)](content/laravel/cache)
  - [Collections (15)](content/laravel/collections)
  - [Configuration (8)](content/laravel/configuration)
  - [Database (16)](content/laravel/database)
  - [Eloquent (99)](content/laravel/eloquent)
  - [Events (3)](content/laravel/events)
  - [HTTP & API (22)](content/laravel/http-api)
  - [Mail (1)](content/laravel/mail)
  - [Queue (18)](content/laravel/queue)
  - [Routing (17)](content/laravel/routing)
  - [Storage (2)](content/laravel/storage)
  - [Testing (12)](content/laravel/testing)
  - [Utilities (33)](content/laravel/utilities)
  - [Validation (19)](content/laravel/validation)
- **PHP (22)**
  - [Basics (4)](content/php/basics)
  - [Performance (1)](content/php/performance)
  - [Strings (1)](content/php/strings)
  - [Syntax (8)](content/php/syntax)
  - [Tooling (8)](content/php/tooling)
- **Git (7)**
  - [Github Actions (4)](content/git/github-actions)
  - [Workflow (3)](content/git/workflow)
- **Pest PHP (5)**
  - [Plugins (2)](content/pest-php/plugins)
  - [Testing (3)](content/pest-php/testing)
- **CSS (3)**
  - [Styling (3)](content/css/styling)
- **Filament (2)**
  - [Admin Panel (2)](content/filament/admin-panel)
- **Javascript (2)**
  - [Frameworks (2)](content/javascript/frameworks)
- **Livewire (2)**
  - [Components (1)](content/livewire/components)
  - [Performance (1)](content/livewire/performance)
- **MySQL (1)**
  - [Queries (1)](content/mysql/queries)
- **Tailwind CSS (1)**
  - [Styling (1)](content/tailwind-css/styling)
<!-- TIPS_INDEX:END -->

## Contributing

Contributions are welcome. If you have an idiomatic tip or fix to share:

1. Fork this repository.
2. Create a new `.md` file inside the appropriate `content/<category>/<subcategory>/` directory.
3. Include the standard YAML frontmatter at the top of the file:

```yaml
---
category: "Laravel"
subcategory: "Eloquent"
tags: ["Laravel", "Eloquent"]
date: "YYYY-MM-DD"
author: "Your Name"
author_url: "https://x.com/yourhandle"
---
```

4. Open a Pull Request explaining the tip and what problem it solves.

## Writing Tips

Keep tips focused and practical:

- Focus on one clear idea or solution.
- Provide concise, working code examples.
- Explain the engineering rationale or outcome.
- Ensure technical accuracy for Laravel and PHP developers.

## License

MIT License.
