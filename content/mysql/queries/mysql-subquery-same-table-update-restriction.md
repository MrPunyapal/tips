---
category: "MySQL"
tags: ["MySQL", "Database", "SQL"]
date: "2023-09-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Queries"
---

# Work Around MySQL Subquery Restrictions on Target Tables

> MySQL prevents updating a table while selecting from it in a subquery. Wrap subqueries in an intermediate alias table.

Executing UPDATE table WHERE id IN (SELECT id FROM table) throws MySQL Error 1093. Work around this by wrapping the subquery in an intermediate derived table alias.

```sql
-- ❌ FAILS in MySQL: Error 1093
-- UPDATE users SET status = 'inactive' WHERE id IN (SELECT id FROM users WHERE last_login < '2023-01-01');

-- ✅ WORKS: Intermediate alias subquery
UPDATE users SET status = 'inactive'
WHERE id IN (
    SELECT id FROM (
        SELECT id FROM users WHERE last_login < '2023-01-01'
    ) AS temp_users
);
```

- MySQL forbids modifying a target table used directly in a subquery clause
- Wrapping subquery in SELECT * FROM (...) AS alias resolves Error 1093
- Alternative: Use JOIN syntax for multi-table updates
