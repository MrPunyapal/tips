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

Migrating large file storage backends (such as transitioning terabytes of user uploads from an expensive legacy S3 bucket to Cloudflare R2 or MinIO) is traditionally an operational headache. A full upfront migration requires batch copy scripts, handling synchronization delays, and coordinating downtime before updating application disk configurations.

Laravel 13.26 introduces the `read-through` filesystem driver. It pairs a **primary disk** with a **fallback disk**, allowing applications to serve existing files from the fallback storage while automatically promoting requested files to the primary storage on demand.

## How the Read-Through Flow Works

When an application reads a file through a read-through disk, Laravel resolves the request through a tiered lookup:

```text
Read requested
      ↓
Check primary disk
   ↙       ↘
found     missing
  ↓          ↓
serve    read from fallback
             ↓
       promote/copy to primary
             ↓
           serve
```

1. **Check Primary**: Laravel checks whether the file exists on the primary disk. If found, it is served immediately.
2. **Fallback Read**: If missing from primary, Laravel reads the file stream from the fallback disk.
3. **Lazy Promotion**: By default, the file is automatically copied/promoted to the primary disk.
4. **Response**: The file content is returned to the application.

Subsequent reads for the same path hit the primary disk directly without touching the fallback storage.

## Configuration

Define a `read-through` disk in `config/filesystems.php` referencing your primary and fallback disks:

```php
// config/filesystems.php
'disks' => [

    'r2' => [
        'driver' => 's3',
        'key' => env('CLOUDFLARE_R2_KEY'),
        'secret' => env('CLOUDFLARE_R2_SECRET'),
        'bucket' => env('CLOUDFLARE_R2_BUCKET'),
        'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
    ],

    'legacy-s3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'bucket' => env('AWS_BUCKET'),
        'region' => env('AWS_DEFAULT_REGION'),
    ],

    'assets' => [
        'driver' => 'read-through',
        'primary' => 'r2',
        'fallback' => 'legacy-s3',
    ],

],
```

Application code reads and writes through the composite `assets` disk as usual:

```php
use Illuminate\Support\Facades\Storage;

// Reads check 'r2' first, fall back to 'legacy-s3', and promote to 'r2'
$avatar = Storage::disk('assets')->get('avatars/user-102.jpg');
```

## Writes, Deletes, and Directory Listings

The `read-through` driver is specifically designed for read fallback and lazy promotion. All write and state operations route strictly to the **primary disk**:

- **Writes (`put`, `writeStream`)**: Saved directly to the primary disk. The fallback disk is not modified.
- **Deletes (`delete`, `deleteDirectory`)**: Removed from the primary disk. The fallback disk is untouched.
- **Directory Listings (`files`, `allFiles`, `directories`)**: Scanned exclusively from the primary disk.

## Disabling Promotion with No-Copy Mode

If you want to read legacy files without writing them to the primary disk, disable automatic promotion using the `copy` option:

```php
'assets' => [
    'driver' => 'read-through',
    'primary' => 'r2',
    'fallback' => 'legacy-s3',
    'copy' => false,
],
```

This no-copy mode is particularly useful in local development or staging environments where you want to read production files from a shared backup without populating your local disk or incurring write costs.

## Handling Promotion Failures

By default, if promoting a file to the primary disk fails (e.g. due to network timeouts or temporary permission errors), Laravel swallows the copy error and returns the file from the fallback so the user request does not break.

If you prefer to strictly enforce promotion and surface any write issues immediately, enable `throw_on_promotion_failure`:

```php
'assets' => [
    'driver' => 'read-through',
    'primary' => 'r2',
    'fallback' => 'legacy-s3',
    'throw_on_promotion_failure' => true,
],
```

## Important Considerations

- **Lazy vs Bulk Migration**: A read-through disk does not automatically migrate an entire storage bucket in the background. It promotes individual files only when they are accessed.
- **Unrequested Files**: Seldom-used or archived files that are never requested will remain solely on the fallback disk until accessed or purged.
- **Independent Credentials**: Both primary and fallback disks must have valid driver configurations and accessible permissions.

## Summary

- Use the `read-through` driver in Laravel 13.26 to pair a primary storage disk with a fallback storage disk.
- Reads check the primary disk first, retrieve missing files from the fallback disk, and promote them to primary storage lazily.
- Writes, deletes, and directory listings always operate on the primary disk.
- Configure `copy => false` for read-only fallback, and `throw_on_promotion_failure => true` to surface copy errors.
