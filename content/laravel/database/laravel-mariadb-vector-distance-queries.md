---
category: "Laravel"
tags: ["Laravel", "MariaDB", "Database", "AI", "Vector Search"]
date: "2026-08-31"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Database"
---

# Query AI Embeddings with MariaDB Vector Distance in Laravel 13.27

> Laravel 13.27 adds native vector distance query support for MariaDB 11.7+, enabling semantic search and similarity filtering directly through the query builder.

When building AI-powered features (such as semantic document search, recommendation engines, or RAG context retrieval), text is converted into high-dimensional vector embeddings and stored in your database.

Previously, running vector similarity searches in MariaDB required writing database-specific raw SQL statements with `VEC_DISTANCE_EUCLIDEAN()` or `VEC_DISTANCE_COSINE()`.

**Laravel 13.27 brings native vector query methods to MariaDB**, allowing you to filter, sort, and calculate vector distances directly through Eloquent and the query builder.

---

## The Query Builder Workflow

```php
use App\Models\Document;

// User search query converted to float array embeddings
$queryEmbedding = [0.0124, -0.0432, 0.0891, ...];

$documents = Document::query()
    // Keep only sufficiently close vectors
    ->whereVectorDistanceLessThan('embedding', $queryEmbedding, maxDistance: 0.5)
    // Order results from closest to farthest
    ->orderByVectorDistance('embedding', $queryEmbedding)
    // Include the computed distance value in results
    ->selectVectorDistance('embedding', $queryEmbedding, as: 'distance')
    ->limit(10)
    ->get();
```

---

## Vector Query Methods Explained

### 1. `whereVectorDistanceLessThan()`
Filters rows where the distance between the stored column vector and the query vector is below a specified threshold:

```php
// Only match documents within 0.4 distance
$query->whereVectorDistanceLessThan('embedding', $queryEmbedding, maxDistance: 0.4);
```

### 2. `orderByVectorDistance()`
Sorts results by proximity to the query embedding. By default, it sorts in ascending order (`asc`) so the most relevant/closest vectors appear first:

```php
// Closest records first
$query->orderByVectorDistance('embedding', $queryEmbedding, 'asc');
```

### 3. `selectVectorDistance()`
Calculates the exact distance score and attaches it as a virtual attribute on the returned model instance:

```php
$document = Document::query()
    ->selectVectorDistance('embedding', $queryEmbedding, as: 'score')
    ->first();

echo $document->score; // e.g. 0.1428
```

---

## Practical Scenario: Semantic Knowledge Base Search

```text
User Search: "How do I configure Redis queues?"
     ↓
Embedding API (e.g. OpenAI / Ollama)
     ↓
Float Array: [0.012, -0.045, ...]
     ↓
MariaDB Vector Query (VEC_DISTANCE)
     ↓
Top 5 Relevant Help Articles
```

```php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\EmbeddingService;
use Illuminate\Http\Request;

class ArticleSearchController extends Controller
{
    public function __invoke(Request $request, EmbeddingService $embeddings)
    {
        $queryVector = $embeddings->generate($request->input('q'));

        $articles = Article::query()
            ->whereVectorDistanceLessThan('embedding', $queryVector, maxDistance: 0.45)
            ->orderByVectorDistance('embedding', $queryVector)
            ->selectVectorDistance('embedding', $queryVector, as: 'relevance_distance')
            ->limit(5)
            ->get();

        return response()->json($articles);
    }
}
```

The database executes the mathematical distance calculations internally using its native vector engine, returning only matching rows to PHP.

---

## Database Requirements

- **MariaDB Version**: Requires **MariaDB 11.7+**, which introduces native `VECTOR` data types and vector distance functions.
- **Column Definition**: The target column in your migration should be configured to store vector data:
  ```php
  Schema::create('documents', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('content');
      $table->vector('embedding', dimensions: 1536);
      $table->timestamps();
  });
  ```

---

## Summary

- Laravel 13.27 brings first-class vector query builder methods to MariaDB 11.7+.
- `whereVectorDistanceLessThan()` filters rows based on distance thresholds.
- `orderByVectorDistance()` sorts records by nearest similarity.
- `selectVectorDistance()` selects the computed score as a model attribute.
- Replaces raw `VEC_DISTANCE_*` SQL clauses with clean, fluent Eloquent methods.
