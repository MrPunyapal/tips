---
category: "Git"
tags: ["Git", "GitHub Actions", "CI/CD", "Performance"]
date: "2026-08-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "GitHub Actions"
---

# Skip Expensive CI Steps Using git diff Instead of File Timestamps

> File modification timestamps are unreliable in GitHub Actions. Use `git diff` to detect actual changes and conditionally skip expensive workflow steps.

Many build tools use file modification timestamps (`mtime`) to decide what needs rebuilding. This works on local machines because the filesystem tracks when each file was last saved.

In GitHub Actions, `actions/checkout` resets **every file's timestamp** to the moment of checkout. A project with 500 files will have all 500 stamped with the exact same time, regardless of when each was actually last modified.

Any script comparing timestamps to decide "has this source changed since the last build?" will answer "yes" for everything, triggering full rebuilds on every single run.

## The Problem in Practice

Consider a build script that skips PDF generation when the output is newer than the source:

```javascript
import fs from 'node:fs';

const srcStat = fs.statSync('template.html');
const outStat = fs.statSync('output.pdf');

if (outStat.mtimeMs > srcStat.mtimeMs) {
  console.log('PDF is up to date, skipping');
  process.exit(0);
}

// ... expensive PDF generation with Puppeteer
```

Locally, this works perfectly. In CI, both files receive the checkout timestamp, so `outStat.mtimeMs` equals `srcStat.mtimeMs`, and the skip condition fails every time.

---

## The Fix: Use git diff

Git tracks actual content changes regardless of filesystem timestamps. Replace `mtime` checks with `git diff`:

```yaml
# .github/workflows/build.yml
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 2  # Need parent commit for diff

      - name: Detect changed files
        id: changes
        run: |
          if git diff --name-only HEAD~1 -- 'src/resume/**' 'templates/resume.html' | grep -q .; then
            echo "resume_changed=true" >> "$GITHUB_OUTPUT"
          fi
          if git diff --name-only HEAD~1 -- 'src/content/**' | grep -q .; then
            echo "content_changed=true" >> "$GITHUB_OUTPUT"
          fi

      - name: Generate PDF Resume
        if: steps.changes.outputs.resume_changed == 'true'
        run: node scripts/generate-pdf.js

      - name: Generate Screenshots
        if: steps.changes.outputs.content_changed == 'true'
        run: node scripts/generate-screenshots.js
```

### Why fetch-depth: 2?

By default, `actions/checkout` performs a shallow clone with `fetch-depth: 1`, which only contains the latest commit. `git diff HEAD~1` requires the parent commit to exist. Setting `fetch-depth: 2` fetches exactly enough history for a single-commit diff.

---

## Handling repository_dispatch and workflow_dispatch

When workflows are triggered by `repository_dispatch` or `workflow_dispatch`, there is no new commit to diff against. Handle these triggers explicitly:

```yaml
- name: Detect changed files
  id: changes
  run: |
    EVENT="${{ github.event_name }}"

    # Dispatch events have no associated commit diff
    if [[ "$EVENT" == "repository_dispatch" || "$EVENT" == "workflow_dispatch" ]]; then
      echo "content_changed=true" >> "$GITHUB_OUTPUT"
      exit 0
    fi

    if git diff --name-only HEAD~1 -- 'src/content/**' | grep -q .; then
      echo "content_changed=true" >> "$GITHUB_OUTPUT"
    fi
```

For `repository_dispatch`, you often know which subsystem triggered the event based on the `event_type`. Use that to set only the relevant flags instead of enabling everything.

---

## The Same Pattern Inside Build Scripts

You can also move the `git diff` check directly into your build scripts:

```javascript
import { execSync } from 'node:child_process';

function hasSourceChanged(paths) {
  try {
    const diff = execSync(
      `git diff --name-only HEAD~1 -- ${paths.join(' ')}`,
      { encoding: 'utf-8' }
    ).trim();
    return diff.length > 0;
  } catch {
    // If git diff fails (shallow clone, initial commit), assume changed
    return true;
  }
}

if (!hasSourceChanged(['template.html', 'styles/resume.css'])) {
  console.log('No source changes detected, skipping generation');
  process.exit(0);
}

// ... proceed with expensive work
```

The `try/catch` fallback is important. On initial commits, force pushes, or misconfigured shallow clones, `git diff HEAD~1` will fail. Defaulting to "changed" ensures the build still runs when change detection is unavailable.

---

## Key Points

- `actions/checkout` sets all file timestamps to the checkout time, making `mtime`-based incremental logic unreliable in CI.
- Use `git diff --name-only HEAD~1` to detect files that actually changed in the latest commit.
- Set `fetch-depth: 2` in `actions/checkout` so the parent commit is available for diffing.
- Handle `repository_dispatch` and `workflow_dispatch` triggers separately, since they have no associated commit diff.
- Always fall back to "assume changed" when `git diff` fails, so builds are never silently skipped due to detection errors.
