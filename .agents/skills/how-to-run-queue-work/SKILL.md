---
name: how-to-run-queue-work
description: Zero-to-Hero instructions on managing Laravel queue workers for background jobs (like Filament exports).
---

# Skill: Managing Queue Workers (Zero to Hero)

Many background tasks (like bulk exports, sending emails, processing large CSVs) require a queue worker to function properly. Without a worker, jobs sit in the database forever and never execute.

## Phase 1: Understanding the Connection

Laravel checks the `QUEUE_CONNECTION` variable in your `.env` file to know how to handle background tasks.

**Synchronous (Local Testing):**
```env
QUEUE_CONNECTION=sync
```
If set to `sync`, jobs run immediately inside the browser request. This is great for local testing without needing a worker, but terrible for production because it freezes the user's browser until the job finishes.

**Asynchronous (Production & Database):**
```env
QUEUE_CONNECTION=database
```
Jobs are pushed to the `jobs` database table. A background worker must be running to process them.

## Phase 2: Running the Worker Locally

If your connection is `database`, you must manually start a worker in your terminal.

1. **Start the Worker:**
   ```bash
   php artisan queue:work
   ```
   This command keeps running and listens for new jobs. Leave this terminal window open!

2. **Debugging Jobs (Run Once):**
   If you want to just process the current jobs in the queue and then exit automatically (great for debugging):
   ```bash
   php artisan queue:work --once
   ```

3. **Clearing Failed Jobs:**
   If a job crashes, it goes to the `failed_jobs` table.
   ```bash
   php artisan queue:retry all
   # OR
   php artisan queue:flush
   ```

## Phase 3: Setup for Production (Supervisor)

In a live production environment, you cannot just run `php artisan queue:work` and close your terminal, because the worker will stop. You must configure a process monitor like **Supervisor** (on Linux).

1. Install supervisor on your server (e.g., Ubuntu):
   ```bash
   sudo apt-get install supervisor
   ```
2. Create a config file at `/etc/supervisor/conf.d/laravel-worker.conf`:
   ```ini
   [program:laravel-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/your/app/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/path/to/your/app/worker.log
   ```
3. Start the process:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start laravel-worker:*
   ```

### Pro-Tips
- **Code Changes:** If you change your PHP code, the running queue worker *will not* pick up the changes. You must restart it: `php artisan queue:restart`.
- **Tries & Timeouts:** Always specify `--tries=3` and `--timeout=90` to prevent jobs from hanging forever or crashing infinitely.
