# Laravel Tips

Curated Laravel, PHP, and Pest engineering tips by [Punyapal Shah](https://mrpunyapal.dev/) and the community.

## Documentation Site

- **Live Documentation**: [https://mrpunyapal.github.io/tips/](https://mrpunyapal.github.io/tips/)
- **Built with**: [DocSmith](https://github.com/MrPunyapal/docsmith) static documentation generator

The interactive documentation website is statically generated directly from the Markdown files maintained in this repository and deployed to GitHub Pages.

## What You'll Find

Practical, bite-sized engineering tips covering Laravel, PHP, and the wider ecosystem. Topics include Eloquent, Livewire, Flux, Filament, testing with Pest PHP, queues, performance optimization, validation, APIs, and everyday developer workflows.

## Category & Subcategory Directory Index

Explore **64** engineering tips directly in the repository by category:

<!-- TIPS_INDEX:START -->
- **Laravel (43)**
  - [Architecture (3)](content/laravel/architecture)
  - [Collections (1)](content/laravel/collections)
  - [Configuration (3)](content/laravel/configuration)
  - [Database (4)](content/laravel/database)
  - [Eloquent (17)](content/laravel/eloquent)
  - [Events (1)](content/laravel/events)
  - [HTTP & API (2)](content/laravel/http-api)
  - [Queue (5)](content/laravel/queue)
  - [Testing (5)](content/laravel/testing)
  - [Utilities (1)](content/laravel/utilities)
  - [Validation (1)](content/laravel/validation)
- **PHP (10)**
  - [Basics (3)](content/php/basics)
  - [Performance (1)](content/php/performance)
  - [Tooling (6)](content/php/tooling)
- **Git (5)**
  - [Github Actions (4)](content/git/github-actions)
  - [Workflow (1)](content/git/workflow)
- **CSS (2)**
  - [Styling (2)](content/css/styling)
- **Livewire (2)**
  - [Components (1)](content/livewire/components)
  - [Performance (1)](content/livewire/performance)
- **MySQL (1)**
  - [Queries (1)](content/mysql/queries)
- **Pest PHP (1)**
  - [Plugins (1)](content/pest-php/plugins)
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
