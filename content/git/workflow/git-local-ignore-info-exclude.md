---
category: "Git"
tags: ["Git", "DevOps", "Workflow", "Tooling"]
date: "2026-08-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Workflow"
---

# Ignore Local Files Without Modifying .gitignore Using .git/info/exclude

> Use .git/info/exclude to ignore personal scratch files, local debug scripts, and notes without polluting your team's shared .gitignore file.

When working on a shared repository, you often create temporary files for local testing: a quick scratchpad (`scratchpad.md`), a temporary debugging script (`debug_local.php`), or a folder of personal investigation notes (`/notes/`).

A common developer habit is adding those personal patterns to the project's root `.gitignore` file. However, `.gitignore` is committed and tracked in version control. Adding personal, one-off file patterns creates noisy pull request diffs and forces team members to adopt ignore rules they do not need.

Git provides a built-in repository-local ignore file: `.git/info/exclude`.

## Three Scopes of Git Ignore Rules

Understanding where to place an ignore pattern depends on who needs the rule:

```text
.gitignore
  ↓
Shared project rules (Committed and tracked by the entire team)

.git/info/exclude
  ↓
Personal rules for this clone only (Uncommitted, stored inside .git/)

Global Gitignore (~/.gitignore)
  ↓
Personal rules across all repositories on your machine (e.g. .DS_Store, .vscode/)
```

## How to Use .git/info/exclude

Every Git repository includes a `.git/info/exclude` file by default. Because it lives inside the `.git` directory, it is never committed or pushed to remote repositories.

Open the file in your preferred editor:

```bash
code .git/info/exclude
```

Add your personal patterns using standard `.gitignore` syntax:

```text
# Personal scratchpad and experimental scripts
scratchpad.md
debug_local.php
experimental_*.php

# Local private notes
/personal-notes/

# Local custom test database dump
local_dump.sql
```

After saving the file, running `git status` will no longer report matching files under "Untracked files", and running `git add .` will not accidentally stage them.

## Important: .git/info/exclude Only Ignores Untracked Files

Like `.gitignore`, adding a pattern to `.git/info/exclude` applies strictly to **untracked files**.

If a file is already tracked in Git history, adding it to `.git/info/exclude` will not prevent Git from tracking subsequent changes to it.

If you need to stop tracking a file that was previously committed while keeping your local copy on disk, you must remove it from the Git index:

```bash
git rm --cached path/to/file.txt
```

*(Note: `git rm --cached` stages a deletion in Git history for the next commit, so only use it if the file should truly be removed from the shared repository for all contributors).*

## Decision Guide: Which Ignore Scope to Use

| Situation | Recommended Scope | Location |
|---|---|---|
| Project dependencies, build outputs, and shared environment templates (`node_modules/`, `vendor/`, `dist/`, `.env.example`) | Project `.gitignore` | `.gitignore` in repository root |
| Personal scratch files, debug scripts, or local test fixtures specific to this clone (`scratchpad.md`, `debug_test.php`, `/my-notes/`) | Local repository exclude | `.git/info/exclude` |
| Machine-wide artifacts and OS/IDE files across all projects on your laptop (`.DS_Store`, `Thumbs.db`, `.idea/`, `.vscode/`) | Global Gitignore | `~/.gitignore` (configured via `git config --global core.excludesFile`) |

## Summary

- Use `.gitignore` for shared project ignore rules that every team member needs.
- Use `.git/info/exclude` to ignore personal scratch files and temporary scripts in a specific repository without modifying committed files.
- Use a global Gitignore (`core.excludesFile`) for operating system and editor artifacts across all repositories on your machine.
