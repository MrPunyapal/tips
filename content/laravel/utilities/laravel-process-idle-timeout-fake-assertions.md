---
category: "Laravel"
tags: ["Laravel", "Process", "Testing", "CLI", "Architecture"]
date: "2026-08-22"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Utilities"
---

# Process Improvements in Laravel 13.26: Idle Timeouts and Testing Assertions

> Laravel 13.26 introduces ProcessIdleTimedOutException to distinguish idle processes from overall runtime timeouts, alongside new testing assertions for execution count and sequence order.

Running external shell processes and CLI commands in Laravel is handled by the `Process` facade. When managing long-running tasks such as deployment scripts, data exports, or media processing, diagnosing process failures and writing comprehensive automated tests are critical requirements.

Laravel 13.26 improves the `Process` facade in two core areas:
1. **Granular Timeout Exceptions**: Differentiating between silent/idle processes and processes that exceed total execution time.
2. **Enhanced Process Fakes**: New test assertions for process execution count (`assertRanCount`), sequence ordering (`assertRanInOrder`), and recorded interaction inspection (`recorded()`).

---

## 1. Overall Timeout vs. Idle Timeout

When executing an external command, two distinct timeout conditions can occur:
- **Overall Timeout (`timeout`)**: The maximum total duration the process is allowed to run from start to finish.
- **Idle Timeout (`idleTimeout`)**: The maximum allowed duration without the process emitting any standard output (`stdout` or `stderr`).

Before Laravel 13.26, exceeding either limit threw the generic `Illuminate\Process\Exceptions\ProcessTimedOutException`. In Laravel 13.26, idle timeouts trigger a dedicated exception: `Illuminate\Process\Exceptions\ProcessIdleTimedOutException`.

### Differentiating Process Failures

```php
use Illuminate\Process\Exceptions\ProcessIdleTimedOutException;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Facades\Process;

try {
    $result = Process::timeout(600)      // Total runtime limit: 10 minutes
        ->idleTimeout(30)               // Output inactivity limit: 30 seconds
        ->run('./deploy-services.sh');

    if ($result->successful()) {
        logger()->info('Deployment completed successfully.');
    }
} catch (ProcessIdleTimedOutException $e) {
    // The process stopped producing output for 30 seconds (e.g. waiting on a prompt or stuck in deadlock)
    logger()->error('Process stalled with no output: ' . $e->getMessage());
} catch (ProcessTimedOutException $e) {
    // The process continued producing output but exceeded the 10-minute overall limit
    logger()->error('Process exceeded total execution budget: ' . $e->getMessage());
}
```

This separation allows automated pipelines to take targeted recovery steps, such as terminating unresponsive processes immediately when idle while permitting active, progressing tasks to run up to their total timeout limit.

---

## 2. Process Fake Testing Assertions

Laravel's `Process::fake()` allows tests to mock process execution without executing actual shell commands. Laravel 13.26 adds dedicated assertion methods to inspect execution count, ordering, and recorded instances.

### Asserting Execution Count with `assertRanCount()`

`Process::assertRanCount()` verifies the exact number of commands invoked during a test:

```php
use Illuminate\Support\Facades\Process;

test('deployment command executes expected subprocesses', function () {
    Process::fake([
        'git pull' => Process::result('Already up to date.'),
        'composer install --no-dev' => Process::result('Installing dependencies...'),
        'php artisan migrate --force' => Process::result('Migrated successfully.'),
    ]);

    $this->artisan('app:deploy');

    // Asserts that exactly 3 processes were executed
    Process::assertRanCount(3);
});
```

This prevents subtle bugs where an unexpected branching condition executes a command multiple times or skips an intended execution silently.

### Asserting Command Sequence with `assertRanInOrder()`

When external tasks depend on strict chronological execution (such as pulling latest source code before migrating database tables), `Process::assertRanInOrder()` verifies the exact sequence:

```php
use Illuminate\Support\Facades\Process;

test('build pipeline executes steps in chronological sequence', function () {
    Process::fake();

    $this->artisan('app:build');

    // Asserts commands ran in this exact order
    Process::assertRanInOrder([
        'npm ci',
        'npm run build',
        'php artisan config:cache',
        'php artisan route:cache',
    ]);
});
```

### Inspecting Recorded Results with `recorded()`

The `Process::recorded()` method returns a collection of all executed processes and their corresponding result objects. You can also pass a closure to filter and inspect specific process runs:

```php
use Illuminate\Process\PendingProcess;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Support\Facades\Process;

test('inspects specific command payloads', function () {
    Process::fake();

    $this->artisan('reports:generate');

    // Filter recorded interactions
    $exportProcesses = Process::recorded(function (PendingProcess $process, ProcessResult $result) {
        return str_contains($process->command, 'mysqldump');
    });

    expect($exportProcesses)->toHaveCount(1);
});
```

---

## Summary

- **`ProcessIdleTimedOutException`**: Catch output inactivity separately from total runtime timeouts via `idleTimeout()`.
- **`Process::assertRanCount()`**: Assert the total number of process invocations in tests.
- **`Process::assertRanInOrder()`**: Verify strict chronological command execution order.
- **`Process::recorded()`**: Inspect and filter recorded process and result pairs using closures.
