---
name: how-to-run-queue-work
description: Instructions on how to manage and run Laravel queue workers for background jobs (like Filament exports).
---

# Skill: How to Run Queue Workers

This skill explains how to manage Laravel queues, which is essential for processing background jobs like Filament Bulk Exports, sending emails, and other deferred tasks.

## Why Do We Need a Queue Worker?
By default, long-running tasks in Laravel and Filament (like exporting thousands of rows to CSV) are pushed to a background queue. If the queue worker isn't running, the job just sits in the database and the task never finishes (e.g., users will never receive the "Export completed" notification with the download link).

## Local Development vs. Production

### 1. Local Development (Instant Processing)
For local development, it is often easier to process jobs instantly instead of running a separate queue worker terminal.

**To do this, update your .env file:**
`env
QUEUE_CONNECTION=sync
`
*Note: With sync, jobs execute immediately in the same request. You don't need to run any artisan queue commands.*

### 2. Local Development (Simulating Production)
If you need to test actual queued jobs (to see how background processing feels):

1. Set your .env:
`env
QUEUE_CONNECTION=database
`
2. Open a dedicated terminal window and run:
`ash
php artisan queue:work
`
*(Leave this terminal running in the background).*

### 3. Production Deployment
In production, sync should **never** be used. You should use database or edis.
You must run the queue worker persistently using a process monitor like **Supervisor** (or Laravel Forge/Vapor).

**Example Supervisor configuration (/etc/supervisor/conf.d/laravel-worker.conf):**
`ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=forge
numprocs=8
redirect_stderr=true
stdout_logfile=/path-to-your-project/storage/logs/worker.log
stopwaitsecs=3600
`

## Useful Queue Commands
- php artisan queue:work - Starts the queue worker and keeps running.
- php artisan queue:work --once - Processes exactly one job and then stops.
- php artisan queue:work --stop-when-empty - Processes all pending jobs and then stops.
- php artisan queue:clear - Deletes all jobs from the default queue.
- php artisan queue:retry all - Retries all failed jobs.
- php artisan queue:failed - Lists all failed jobs.
