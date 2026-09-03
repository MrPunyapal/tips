---
category: "Git"
tags: ["Git", "GitHub Actions", "CI/CD", "Build Tools"]
date: "2026-08-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "GitHub Actions"
---

# Write Files Incrementally in Build Scripts to Avoid Unnecessary Rebuilds

> Compare generated content against existing files before writing. This prevents unnecessary git diffs, cache invalidation, and downstream rebuilds.

Build scripts that generate HTML, JSON, XML, or other output files often write the result unconditionally, even when the content has not changed. This creates problems in CI pipelines: unchanged files get new timestamps, `git status` reports false modifications, auto-commit steps push empty diffs, and downstream caches are invalidated for no reason.

## The Problem

A typical build script writes output directly:

```javascript
import fs from 'node:fs';

const html = generatePage(data);
fs.writeFileSync('dist/index.html', html);
```

If `generatePage` produces the same HTML as the file already on disk, the write is wasted. Worse, it updates the file's modification timestamp, which can trigger:

- `git diff` reporting the file as changed (if line endings or encoding differ)
- Downstream tools rebuilding dependents of that file
- CI auto-commit steps creating empty or noise-only commits
- CDN or deployment cache invalidation for files that did not actually change

---

## The Fix: Compare Before Writing

Read the existing file, compare it with the new content, and skip the write when they match:

```javascript
import fs from 'node:fs';
import path from 'node:path';

function writeIfChanged(filePath, content) {
  try {
    const existing = fs.readFileSync(filePath, 'utf-8');
    if (existing === content) return false;
  } catch {
    // File does not exist yet, proceed with write
  }

  fs.mkdirSync(path.dirname(filePath), { recursive: true });
  fs.writeFileSync(filePath, content);
  return true;
}
```

Usage in a build script:

```javascript
const pages = generateAllPages(data);
let written = 0;

for (const [filePath, html] of Object.entries(pages)) {
  if (writeIfChanged(filePath, html)) {
    written++;
  }
}

console.log(`Wrote ${written}/${Object.keys(pages).length} files (rest unchanged)`);
```

On a typical run where 3 out of 300 pages changed, you will see:

```text
Wrote 3/300 files (rest unchanged)
```

---

## Apply to Every Generated Output

This pattern works for any generated file type:

```javascript
// HTML pages
writeIfChanged('dist/tips.html', renderTipsPage(tips));

// JSON search indexes
writeIfChanged('public/search-index.json', JSON.stringify(index));

// XML feeds and sitemaps
writeIfChanged('public/feed.xml', renderRssFeed(posts));
writeIfChanged('public/sitemap.xml', renderSitemap(routes));
```

---

## CI Auto-Commit: Only Commit When Files Actually Changed

When your workflow auto-commits generated files, the incremental write pattern prevents empty commits:

```yaml
- name: Generate static assets
  run: node scripts/build.js

- name: Commit generated files if changed
  run: |
    git add -A
    if git diff --cached --quiet; then
      echo "No changes to commit"
    else
      git commit -m "chore: regenerate static assets"
      git push
    fi
```

Without incremental writes, `git diff --cached` would always detect changes because every file received a fresh write (and potentially a new timestamp or trailing whitespace difference), leading to commits that contain no meaningful content changes.

---

## Key Points

- Always compare new content with existing files before writing in build scripts.
- Skipping unchanged writes prevents false `git diff` results, unnecessary cache invalidation, and empty auto-commits.
- The `try/catch` pattern handles missing files gracefully on first builds.
- This optimization compounds in projects with hundreds of generated files, where only a few change per commit.
- Pair with `git diff --cached --quiet` in CI to avoid committing when nothing actually changed.
