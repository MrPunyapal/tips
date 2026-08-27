---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Database", "Queries"]
date: "2022-10-12"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Eloquent"
---

# Filter Eloquent Queries with whereBelongsTo()

> Use whereBelongsTo() to filter queries by related parent models without manually hardcoding foreign key column names.

Filtering child models by their parent relation usually involves specifying foreign keys explicitly (such as `where('company_id', $company->id)`). 

The `whereBelongsTo()` method infers foreign keys and primary keys automatically from the model relationship definitions.

## Basic Usage

```php
use App\Models\Company;
use App\Models\User;

$company = Company::find(1);

// Standard manual foreign key check
$users = User::where('company_id', $company->id)->get();

// Clean, relationship-aware query
$users = User::whereBelongsTo($company)->get();
```

## Specifying Named Relationships

If your model defines custom relationship names (for example, `manager` instead of `user`), pass the relationship name as the second argument:

```php
use App\Models\Project;
use App\Models\User;

$manager = User::find(5);

// Explicit relationship name
$projects = Project::whereBelongsTo($manager, 'manager')->get();
```

## Filtering by Collections of Models

`whereBelongsTo()` also accepts Eloquent collections to perform an automatic `WHERE IN` query:

```php
$vipCompanies = Company::where('tier', 'vip')->get();

// Generates: WHERE company_id IN (1, 2, 3)
$users = User::whereBelongsTo($vipCompanies)->get();
```

## Summary

- Automatically resolves foreign keys and primary keys from model relationships.
- Prevents bugs caused by hardcoded foreign key column names when relationships change.
- Supports both single model instances and model collections for `WHERE IN` operations.
