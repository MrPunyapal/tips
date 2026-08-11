---
category: "Git"
tags: ["Git", "GitHub Actions", "CI/CD", "DevOps"]
date: "2026-08-11"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "GitHub Actions"
---

# Trigger a GitHub Actions Workflow in Another Repository

> Use `repository_dispatch` to trigger a workflow in a different repository when a PR is merged or code is pushed.

GitHub repositories are isolated — merging a PR in Repo A will never automatically trigger a workflow in Repo B. The `repository_dispatch` event bridges this gap.

## 1. Set Up the Listener (Target Repo)

In the repository where you want the workflow to **run**, add `repository_dispatch` as a trigger:

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
      - run: echo "Build triggered!"
```

## 2. Set Up the Notifier (Source Repo)

In the repository that should **send** the signal, add a workflow that fires a `curl` request:

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
      - name: Trigger target repo build
        run: |
          curl -X POST \
            -H "Authorization: Bearer ${{ secrets.DISPATCH_TOKEN }}" \
            -H "Accept: application/vnd.github.v3+json" \
            https://api.github.com/repos/OWNER/TARGET-REPO/dispatches \
            -d '{"event_type": "content-updated"}'
```

## 3. Create the Token

- Go to GitHub **Settings** → **Developer Settings** → **Fine-Grained Personal Access Tokens**
- Scope it to the **target repository** only
- Grant **Contents: Read and write** permission
- Save the token as `DISPATCH_TOKEN` in the **source repository's** Actions secrets

- The `event_type` string must match exactly between the sender and the listener
- The token needs write access to the **target** repo, stored as a secret in the **source** repo
- Works for any cross-repo automation: monorepo builds, submodule syncs, deploy triggers
