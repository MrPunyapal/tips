---
category: "Laravel"
tags: ["Laravel", "Eloquent", "Enums"]
date: "2024-03-16"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
---

# Cast Model Attributes to Backed Enums in Eloquent

> Define PHP Backed Enums on model $casts to automatically hydrate strings and integers into typed Enums.

Storing status strings like 'active' or 'pending' as raw strings leads to typos. Defining Backed Enums inside model $casts makes sure typed enum object hydration.

```php
namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
        ];
    }
}
```

- Automatically casts database scalar values into typed Backed Enum objects
- Provides full type safety and IDE auto-completion on model attributes
- Throws ValueError when database contains unmapped enum scalar values
