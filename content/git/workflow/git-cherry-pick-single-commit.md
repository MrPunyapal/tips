---
category: "Git"
tags: ["Git", "Version Control", "Workflow", "CLI"]
date: "2026-08-29"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Workflow"
---

# Apply Individual Commits Across Branches with Git cherry-pick

> Use git cherry-pick to apply specific commits from another branch to your current branch without merging the entire branch history.

When working across multiple feature branches, you often write a useful bug fix, documentation tweak, or isolated utility method on a feature branch that is still in development.

Merging the entire feature branch into `main` would pull in unfinished work and untested features.

Instead of manually copy-pasting the changes, **`git cherry-pick`** allows you to select a specific commit from any branch and apply its diff directly onto your current branch.

---

## The Basic Workflow

```bash
# 1. Switch to the target branch that should receive the change
git checkout main

# 2. Apply the specific commit by its hash
git cherry-pick 4ecc3a3

# 3. Push the newly created commit to the remote repository
git push origin main
```

*(Note: `4ecc3a3` is an example commit hash. Replace it with your actual commit SHA from `git log`.)*

---

## What Actually Happens?

```text
feature-branch
    ├── commit A (WIP UI refactor)
    ├── commit B (Bug fix for auth redirect)  ← Need this on main
    └── commit C (WIP checkout page)

git checkout main
git cherry-pick <commit-B-hash>

main
    └── commit B' (New commit with identical diff, new SHA)
```

When you run `git cherry-pick 4ecc3a3`:
1. Git extracts the exact patch (diff) introduced by commit `4ecc3a3`.
2. It applies those changes onto the working tree of your currently checked-out branch.
3. It creates a **new commit** with a new commit hash and timestamp on your current branch.

The original commit on the source branch remains untouched.

---

## Cherry-Pick vs. Merge

| Operation | Command | What It Does |
|---|---|---|
| **Merge** | `git merge feature` | Combines two full branch histories together, including all commits and ancestors. |
| **Cherry-Pick** | `git cherry-pick <hash>` | Copies only the specific changes from selected commits into new commits on the active branch. |

Use `git merge` when an entire feature is finished and ready for integration. Use `git cherry-pick` when you need an isolated fix without bringing over the surrounding branch work.

---

## Handling Conflicts

If the files modified by the cherry-picked commit have diverged significantly on your target branch, Git will pause and flag a conflict.

### To resolve and continue:

```bash
# 1. Resolve conflict markers in your editor, then stage the resolved files
git add .

# 2. Continue the cherry-pick operation
git cherry-pick --continue
```

### To cancel and return to clean state:

```bash
# Aborts the cherry-pick and returns your branch to its pre-cherry-pick state
git cherry-pick --abort
```

---

## Cherry-Picking Multiple Commits

You can also cherry-pick multiple commits in a single operation:

```bash
# Cherry-pick individual non-contiguous commits
git cherry-pick abc1234 def5678

# Cherry-pick a contiguous range of commits (from A up to B, exclusive of A)
git cherry-pick abc1234..def5678

# Cherry-pick a contiguous range including A (inclusive)
git cherry-pick abc1234^..def5678
```

---

## Practical Scenarios

- **Hotfixes on Production Branches**: A bug was resolved on `main` or a `develop` branch and needs to be backported immediately to a live `v1.x` release branch.
- **Accidental Commits**: You accidentally committed a change to the wrong branch. You can cherry-pick it onto the correct branch and reset the original branch.
- **Extracted Refactors**: While building a long-running feature, you wrote a clean helper method that teammates need immediately on `main`.

---

## Summary

- `git cherry-pick <hash>` applies the diff of a specific commit onto your current branch.
- Creates a new commit with its own unique SHA on the active branch without altering the original commit.
- Use `git cherry-pick --continue` after resolving merge conflicts, or `git cherry-pick --abort` to cancel.
- Avoids merging unfinished feature branches when only an isolated change is needed.
