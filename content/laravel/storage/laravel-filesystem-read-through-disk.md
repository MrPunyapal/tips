---
category: "Laravel"
tags: ["Laravel", "Filesystem", "Storage", "Architecture", "DevOps"]
date: "2026-08-21"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Storage"
---

# Lazy Storage Migration with Read-Through Filesystem Disks in Laravel 13.26

> Laravel 13.26 introduces the read-through filesystem driver, combining primary and fallback storage disks to migrate files lazily as they are requested.

Migrating large file storage backends (such as moving terabytes of user uploads from an expensive legacy S3 bucket to Cloudflare R2) usually requires batch copy scripts and synchronized downtime.

Laravel 13.26 adds the `read-through` filesystem driver. It pairs a **primary disk** with a **fallback disk**, serving existing files from fallback storage while automatically promoting requested files to the primary storage on demand.

---

## Configuration

Define a `read-through` disk in `config/filesystems.php` referencing your primary and fallback disks:

```php
// config/filesystems.php
'disks' => [

    'r2' => [
        'driver'   => 's3',
        'key'      => env('CLOUDFLARE_R2_KEY'),
        'secret'   => env('CLOUDFLARE_R2_SECRET'),
        'bucket'   => env('CLOUDFLARE_R2_BUCKET'),
        'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
    ],

    'legacy-s3' => [
        'driver' => 's3',
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'bucket' => env('AWS_BUCKET'),
        'region' => env('AWS_DEFAULT_REGION'),
    ],

    'assets' => [
        'driver'   => 'read-through',
        'primary'  => 'r2',
        'fallback' => 'legacy-s3',
        // 'copy'  => false, // Optional: read fallback without promoting to primary
        // 'throw_on_promotion_failure' => true, // Optional: throw on copy failure
    ],

],
```

---

## How It Operates

```php
use Illuminate\Support\Facades\Storage;

// Reads check 'r2' first; if missing, reads from 'legacy-s3' and promotes to 'r2'
$avatar = Storage::disk('assets')->get('avatars/user-102.jpg');

// Writes always target the primary disk ('r2') directly
Storage::disk('assets')->put('avatars/user-103.jpg', $contents);
```

---

## Operational Mechanics

- **Lazy Promotion**: Files are copied to the primary disk only when accessed. Unrequested files remain on the fallback disk until accessed or purged.
- **Writes & Deletes**: Operations such as `put()`, `writeStream()`, `delete()`, and `deleteDirectory()` target the primary disk exclusively.
- **Directory Scans**: `files()` and `directories()` inspect the primary disk only.
- **Read-Only Fallback (`copy => false`)**: Disables automatic promotion. Useful in local or staging environments to read production buckets without incurring write costs or populating local disks.
- **Fault Tolerance**: By default, if promoting a file to the primary disk fails, Laravel returns the fallback stream so the user request does not break. Set `'throw_on_promotion_failure' => true` to catch write errors in testing.
