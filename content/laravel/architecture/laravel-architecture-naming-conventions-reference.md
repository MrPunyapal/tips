---
category: "Laravel"
tags: ["Laravel", "Architecture", "Best Practices", "Clean Code"]
date: "2024-06-05"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Architecture"
---

# Follow Laravel Standard Naming Conventions for Predictable Architecture

> A complete reference guide to Laravel community naming conventions across models, controllers, migrations, relationships, routes, views, enums, and tests.

Following standard naming conventions allows Laravel's internal reflection engines (including route model binding, Eloquent relation resolution, and migration schema builders) to function automatically without manual configuration.

## Complete Naming Conventions Cheat Sheet

| Component | Convention | Good Example | Bad Example |
|---|---|---|---|
| **Controller** | Singular (PascalCase) | `ArticleController` | `ArticlesController` |
| **Model** | Singular (PascalCase) | `User`, `Order` | `Users`, `Orders` |
| **Migration Table** | Plural (snake_case) | `articles`, `order_items` | `article`, `orderItems` |
| **Pivot Table** | Singular alphabetical (snake_case) | `article_user`, `order_product` | `articles_users`, `user_article` |
| **Table Column** | snake_case without model prefix | `meta_title`, `status` | `MetaTitle`, `article_meta_title` |
| **Model Property** | snake_case | `$user->created_at` | `$user->createdAt` |
| **Primary Key** | Default `id` | `id` | `article_id`, `custom_id` |
| **Foreign Key** | Singular model + `_id` | `user_id`, `order_id` | `user_fk`, `id_user`, `users_id` |
| **Relationship (`hasOne` / `belongsTo`)** | Singular (camelCase) | `user()`, `articleComment()` | `users()`, `article_comment()` |
| **Relationship (`hasMany` / `belongsToMany`)** | Plural (camelCase) | `comments()`, `roles()` | `comment()`, `article_comments()` |
| **Route URI** | Plural (kebab-case) | `/articles/{article}` | `/article/{id}`, `/view_articles` |
| **Route Name** | Plural resource dot notation | `articles.index`, `orders.show` | `show_article`, `articleShow` |
| **View Template** | kebab-case | `show-filtered.blade.php` | `showFiltered.blade.php`, `show_filtered.blade.php` |
| **Config File** | snake_case | `config/google_calendar.php` | `googleCalendar.php`, `google-calendar.php` |
| **Config Key** | snake_case | `config('services.stripe_key')` | `config('services.StripeKey')` |
| **Contract (Interface)** | Adjective or Noun (PascalCase) | `Authenticatable`, `PaymentGateway` | `IAuthentication`, `AuthInterface` |
| **Trait** | Adjective (PascalCase) | `Notifiable`, `Sluggable` | `NotificationTrait`, `TraitNotifiable` |
| **Enum** | Singular (PascalCase) | `UserRole`, `OrderStatus` | `UserRoles`, `UserRoleEnum` |
| **Form Request** | Singular Action + Model + `Request` | `UpdateUserRequest`, `StorePostRequest` | `UserFormRequest`, `UserRequest` |
| **Seeder** | Singular (PascalCase) | `UserSeeder`, `OrderSeeder` | `UsersSeeder`, `OrdersSeeder` |
| **Resource Controller Action** | Standard REST verb | `index`, `store`, `update` | `getAll`, `saveArticle` |
| **Class Method** | camelCase | `getFullName()`, `processPayment()` | `get_full_name()`, `ProcessPayment()` |
| **Test Method** | camelCase or `test_` snake_case | `testUserCanPurchase()`, `it_charges_order()` | `UserCanPurchase` |
| **Collection Variable** | Descriptive plural | `$activeUsers = User::active()->get()` | `$data`, `$active`, `$usersList` |
| **Single Model Variable** | Descriptive singular | `$user = User::first()` | `$users`, `$obj`, `$item` |

## Why Conventions Matter

1. **Automatic Relation Resolution**: Eloquent infers foreign keys and pivot tables automatically without requiring manual string parameters.
2. **Implicit Route Model Binding**: Type-hinting `Article $article` on `/articles/{article}` resolves the model with zero controller boilerplate.
3. **Consistency**: Engineers moving between Laravel projects immediately know where files live and how methods are named.

## Summary

- Eliminates redundant `$table`, `$primaryKey`, and relationship foreign key parameters.
- Guarantees predictable codebase navigation across development teams.
- Follows standard PHP-FIG PSR-12 and Laravel framework conventions.
