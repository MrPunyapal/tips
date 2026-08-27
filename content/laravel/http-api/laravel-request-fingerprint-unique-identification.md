---
category: "Laravel"
tags: ["Laravel", "HTTP", "Rate Limiting", "Security"]
date: "2024-08-28"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "HTTP API"
---

# Identify Client Requests with $request->fingerprint()

> Use $request->fingerprint() to generate unique client identity hashes for rate limiting, deduplication, and guest session tracking.

When rate-limiting public API endpoints or guest voting widgets where users are unauthenticated, relying solely on IP addresses can unfairly throttle entire corporate offices sharing a single proxy IP.

`$request->fingerprint()` combines the HTTP method, host, path, IP address, and user agent into a unique SHA-1 hash.

## Generating a Unique Request Hash

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

public function vote(Request $request, Poll $poll)
{
    // Generate unique client signature
    $clientFingerprint = $request->fingerprint();

    if (RateLimiter::tooManyAttempts("poll-vote:{$poll->id}:{$clientFingerprint}", 1)) {
        return response()->json(['error' => 'You have already voted on this poll.'], 429);
    }

    RateLimiter::hit("poll-vote:{$poll->id}:{$clientFingerprint}", decaySeconds: 86400);

    $poll->increment('votes');

    return response()->json(['success' => true]);
}
```

## Summary

- Produces a deterministic SHA-1 hash from IP, path, method, and user-agent.
- Perfect for guest throttling, spam prevention, and duplicate form submission protection.
- Built-in method on all `Illuminate\Http\Request` instances.
