---
category: "Git"
tags: ["Git", "Version Control", "Workflow", "DevOps", "Monorepo"]
date: "2026-09-01"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Workflow"
---

# Git Submodule vs. Git Subtree: Which Should You Use?

> A practical comparison of Git Submodules and Git Subtrees to help you choose the right strategy for embedding one repository inside another.

When managing projects that share common libraries, documentation sets, microservices, or reusable UI components, you frequently need to embed one Git repository inside another.

Git provides two distinct mechanisms to handle this:
- **Git Submodules**: Keep the external repository strictly independent; the parent repository only stores a pointer to a specific commit SHA.
- **Git Subtrees**: Merge the external repository files and commit history directly into a subdirectory of the parent repository.

---

## 1. Git Submodules: Independent Pointer

With submodules, the external project remains an entirely separate repository. The parent repository does not store the files directly; it only tracks the submodule URL and a commit hash in `.gitmodules`.

```text
Main Repository
├── app/
├── config/
└── packages/shared-lib/  → [Pointer to Commit abc1234 in external repo]
```

### Adding a Submodule

```bash
# Add external repo into packages/shared-lib
git submodule add https://github.com/example/shared-lib.git packages/shared-lib

# Stage and commit the .gitmodules and submodule reference
git commit -m "Add shared-lib submodule"
```

### Cloning a Repository with Submodules

When team members clone a repository containing submodules, standard `git clone` leaves submodule directories empty. They must pass the `--recurse-submodules` flag:

```bash
# Clone parent and initialize all nested submodules
git clone --recurse-submodules https://github.com/example/main-app.git

# Or initialize submodules in an existing clone
git submodule update --init --recursive
```

### Updating a Submodule

To pull the latest changes from the submodule remote branch and update the parent commit pointer:

```bash
git submodule update --remote packages/shared-lib
git add packages/shared-lib
git commit -m "Update shared-lib to latest commit"
```

---

## 2. Git Subtrees: In-Tree Merged History

With subtrees, the external repository's files are committed directly into your repository. Collaborators can clone, pull, and edit files normally without needing extra Git flags or submodule commands.

```text
Main Repository
├── app/
├── config/
└── packages/shared-lib/  → [Real files + merged commit history]
```

### Adding a Subtree

```bash
# 1. Add the external repository as a remote
git remote add shared-lib https://github.com/example/shared-lib.git

# 2. Add the subtree into packages/shared-lib using --squash
git subtree add --prefix=packages/shared-lib shared-lib main --squash
```

### Pulling Upstream Updates

When the external repository publishes new commits, pull them into the parent project:

```bash
git subtree pull --prefix=packages/shared-lib shared-lib main --squash
```

### Pushing Local Changes Upstream

If you make modifications inside `packages/shared-lib` within the parent repo and want to push those commits back to the original repository:

```bash
git subtree push --prefix=packages/shared-lib shared-lib main
```

---

## 3. Side-by-Side Comparison

| Feature | Git Submodule | Git Subtree |
|---|---|---|
| **Storage in Parent Repo** | Only commit SHA reference (`.gitmodules`) | Full file contents and history |
| **Cloning Experience** | Requires `--recurse-submodules` | Normal `git clone` (no extra flags) |
| **Developer Complexity** | Higher (requires submodule workflow knowledge) | Lower (files behave like normal code) |
| **History Isolation** | Completely separate Git trees | Merged into the parent repository tree |
| **Locking Exact Versions** | Strict (pinned to exact commit hash) | Flexible (updated on demand via pull) |
| **Pushing Changes Back** | Direct commits inside submodule directory | Requires `git subtree push` command |
| **Third-Party Dependencies** | Ideal for external code you rarely modify | Ideal for shared code you actively edit |

---

## 4. Which One Should You Choose?

### Choose Git Submodules if:
- The embedded repository has its own strict release cycle and version tags.
- You want the parent repository to lock onto an exact commit hash.
- Team members and CI pipelines are comfortable initializing submodules (`--recurse-submodules`).
- The embedded project is large and you want to keep repository clone sizes small.

### Choose Git Subtrees if:
- You want a friction-free clone experience for team members and contributors.
- You want the external code to behave like ordinary files inside the repository.
- You frequently edit the embedded files directly alongside your application code.
- You want to avoid detached HEAD states and submodule initialization errors in CI.

---

## 5. Common Gotchas

### Submodule Gotchas:
- **Empty Folders on Clone**: Forgetting `--recurse-submodules` leaves submodule folders blank.
- **Uncommitted Pointer Updates**: Making changes inside the submodule folder without committing the updated reference in the parent repository causes inconsistent states between team members.
- **Detached HEAD**: By default, submodules check out detached commits rather than tracking branches.

### Subtree Gotchas:
- **Longer Commands**: Subtree syntax (`--prefix`, `--squash`, remote names) requires precise terminal commands.
- **Repo Bloat**: Importing large repositories with long commit histories increases the size of your parent repository.

---

## Summary

- Use **Git Submodules** when you need strict commit pinning and want the dependency to remain an isolated external project.
- Use **Git Subtrees** when you want shared code to live directly in your repository with direct cloning for collaborators.
