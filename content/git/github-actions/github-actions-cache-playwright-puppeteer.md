---
category: "Git"
tags: ["Git", "GitHub Actions", "CI/CD", "Playwright", "Puppeteer"]
date: "2026-08-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "GitHub Actions"
---

# Cache Playwright and Puppeteer Browsers in GitHub Actions

> Cache browser binaries with `actions/cache` to avoid downloading 150MB+ on every CI run.

Playwright and Puppeteer download full browser binaries (Chromium, Firefox, WebKit) during installation. Without caching, every workflow run downloads these binaries from scratch, adding 30-90 seconds of network time depending on the browser set and runner region.

## Cache Playwright Browsers

```yaml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 22

      - run: npm ci

      - name: Cache Playwright browsers
        id: playwright-cache
        uses: actions/cache@v4
        with:
          path: ~/.cache/ms-playwright
          key: playwright-${{ runner.os }}-${{ hashFiles('package-lock.json') }}

      - name: Install Playwright Chromium
        if: steps.playwright-cache.outputs.cache-hit != 'true'
        run: npx playwright install chromium

      - name: Install Playwright system deps
        run: npx playwright install-deps chromium

      - name: Run browser tasks
        run: node scripts/generate-screenshots.js
```

### How This Works

1. `actions/cache` checks for a cached copy of `~/.cache/ms-playwright` matching the lock file hash.
2. On a cache hit, browser binaries are restored in ~5 seconds instead of downloaded in ~60 seconds.
3. The `install chromium` step is skipped entirely on cache hits.
4. System dependencies (`install-deps`) must run every time because they install OS-level packages (`libnss3`, `libatk1.0`, etc.) that are not persisted across runner instances.

---

## Cache Puppeteer Browsers

Puppeteer stores browsers in a different default location. The pattern is the same with a different cache path:

```yaml
      - name: Cache Puppeteer browsers
        id: puppeteer-cache
        uses: actions/cache@v4
        with:
          path: ~/.cache/puppeteer
          key: puppeteer-${{ runner.os }}-${{ hashFiles('package-lock.json') }}

      - name: Install Puppeteer browser
        if: steps.puppeteer-cache.outputs.cache-hit != 'true'
        run: npx puppeteer browsers install chrome
```

---

## Cache Key Strategy

The cache key `playwright-${{ runner.os }}-${{ hashFiles('package-lock.json') }}` ensures:

- **OS specificity**: Browser binaries are platform-specific. A Linux-cached browser will not work on macOS or Windows runners.
- **Version tracking**: When `playwright` or `puppeteer` is upgraded in `package-lock.json`, the hash changes, and a fresh download is triggered with the correct browser version.

For monorepos or workspaces with multiple lock files, point `hashFiles` to the relevant lock file:

```yaml
key: playwright-${{ runner.os }}-${{ hashFiles('apps/web/package-lock.json') }}
```

---

## Install Only What You Need

Both Playwright and Puppeteer support installing individual browsers. If your workflow only needs Chromium, skip the rest:

```bash
# Playwright: install only Chromium (skip Firefox, WebKit)
npx playwright install chromium

# Puppeteer: install only Chrome
npx puppeteer browsers install chrome
```

Installing all browsers downloads 400MB+ and triples the cache size. Install only what your workflow actually uses.

---

## Combining Cache with Conditional Steps

If some workflow runs do not need a browser at all, combine caching with conditional execution to skip both the cache restore and the install step:

```yaml
      - name: Detect if screenshots needed
        id: changes
        run: |
          if git diff --name-only HEAD~1 -- 'src/content/**' | grep -q .; then
            echo "needs_browser=true" >> "$GITHUB_OUTPUT"
          fi

      - name: Cache Playwright browsers
        if: steps.changes.outputs.needs_browser == 'true'
        id: playwright-cache
        uses: actions/cache@v4
        with:
          path: ~/.cache/ms-playwright
          key: playwright-${{ runner.os }}-${{ hashFiles('package-lock.json') }}

      - name: Install Playwright Chromium
        if: steps.changes.outputs.needs_browser == 'true' && steps.playwright-cache.outputs.cache-hit != 'true'
        run: npx playwright install chromium
```

This avoids restoring a 150MB cache on runs that will never use a browser.

---

## Key Points

- Browser binaries are often the largest single download in Node.js CI workflows.
- Cache `~/.cache/ms-playwright` (Playwright) or `~/.cache/puppeteer` (Puppeteer) using `actions/cache`.
- Include `package-lock.json` hash in the cache key so version upgrades trigger a fresh download.
- System dependencies (`install-deps`) must run on every workflow run, even on cache hits.
- Install only the specific browsers your workflow uses to minimize download and cache size.
