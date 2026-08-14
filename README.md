# Laravel Tips

This repository contains the Markdown source files for **Laravel Tips** published at [mrpunyapal.dev/tips](https://mrpunyapal.dev/tips).

## Website

Published interactive version: [https://mrpunyapal.dev/tips](https://mrpunyapal.dev/tips)
RSS Feed: [https://mrpunyapal.dev/tips/feed.xml](https://mrpunyapal.dev/tips/feed.xml)

The website is statically generated directly from the Markdown files maintained in this repository.

## What You'll Find

Practical, bite-sized engineering tips covering Laravel, PHP, and the wider ecosystem. Topics include Eloquent, Livewire, Flux, Filament, testing with Pest PHP, queues, performance optimization, validation, APIs, and everyday developer workflows.

## Category & Subcategory Directory Index

Explore engineering tips directly in the repository by category:

- **Laravel**
  - [Architecture](content/laravel/architecture)
  - [Blade](content/laravel/blade)
  - [Cache](content/laravel/cache)
  - [Configuration](content/laravel/configuration)
  - [Eloquent](content/laravel/eloquent)
  - [HTTP & API](content/laravel/http-api)
  - [Queue](content/laravel/queue)
  - [Routing](content/laravel/routing)
  - [Utilities](content/laravel/utilities)
  - [Validation](content/laravel/validation)
- **PHP**
  - [Basics](content/php/basics)
  - [Constants](content/php/constants)
  - [Syntax](content/php/syntax)
  - [Tooling](content/php/tooling)
- **Pest PHP**
  - [Plugins](content/pest-php/plugins)
  - [Testing](content/pest-php/testing)
- **Filament**
  - [Admin Panel](content/filament/admin-panel)
- **JavaScript**
  - [DOM](content/javascript/dom)
  - [Frameworks](content/javascript/frameworks)
- **CSS**
  - [Styling](content/css/styling)
- **Git**
  - [Workflow](content/git/workflow)
- **MySQL**
  - [Queries](content/mysql/queries)
- **Tailwind CSS**
  - [Styling](content/tailwind-css/styling)

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
