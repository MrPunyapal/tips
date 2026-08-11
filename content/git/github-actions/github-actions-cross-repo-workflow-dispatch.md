---
category: "Git"
tags: ["Git", "GitHub Actions", "CI/CD", "DevOps"]
date: "2026-08-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "GitHub Actions"
---

# Trigger a GitHub Actions Workflow Across Repositories

> Use `repository_dispatch` to trigger a workflow in a target repository when commits or PRs land in a source repository.

GitHub Actions workflows are scoped to a single repository by default. To run a workflow in another repository after merging a PR or pushing a commit, use GitHub's `repository_dispatch` API endpoint.

## 1. Configure the Target Repository Listener

Add `repository_dispatch` to the `on` block in the target repository workflow file:

```yaml
# .github/workflows/build.yml (target repo)
name: Build Site

on:
  push:
    branches:
      - main
  repository_dispatch:
    types: [content-updated]
  workflow_dispatch:

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: echo "Build triggered"
```

## 2. Configure the Source Repository Dispatcher

In the source repository, add a workflow step that sends a POST request to GitHub's dispatches endpoint:

```yaml
# .github/workflows/notify.yml (source repo)
name: Notify Target Repo

on:
  push:
    branches:
      - main

jobs:
  notify:
    runs-on: ubuntu-latest
    steps:
      - name: Trigger target build
        env:
          TOKEN: ${{ secrets.WEBSITE_DISPATCH_TOKEN }}
        run: |
          if [ -z "$TOKEN" ]; then
            echo "WEBSITE_DISPATCH_TOKEN is not set"
            exit 1
          fi
          curl --fail --show-error -X POST \
            -H "Authorization: Bearer $TOKEN" \
            -H "Accept: application/vnd.github.v3+json" \
            https://api.github.com/repos/OWNER/TARGET-REPO/dispatches \
            -d '{"event_type": "content-updated"}'
```

## 3. Create and Assign the Access Token

1. Create a Fine-Grained Personal Access Token under GitHub Developer Settings.
2. Select the target repository under **Repository Access**.
3. Set **Contents** permission to **Read and write**.
4. Save the token as `WEBSITE_DISPATCH_TOKEN` in the source repository's Actions Secrets.

## Key Considerations

- The `event_type` string in the payload must match the array value under `types: [...]`.
- Always pass `--fail --show-error` to `curl` so HTTP errors exit with code 1 instead of failing silently.
- Fine-grained tokens limit dispatch access strictly to the targeted repository.
