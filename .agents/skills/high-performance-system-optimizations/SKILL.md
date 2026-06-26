---
name: high-performance-system-optimizations
description: Zero-to-Hero instructions on auditing and implementing high-performance optimizations in Laravel Filament (Redis, Eager Loading, Queued Exports, and Database Indexes).
---

# Skill: High Performance System Optimizations

When building or auditing a Laravel Filament application for high-traffic or high-performance production environments, follow these four core pillars of optimization. 

## 1. Database Indexing
Raw query speed is the foundation of high performance. You must ensure that columns used for searching, sorting, and filtering have appropriate database indexes.

- **Check Foreign Keys:** In MySQL, foreign keys created with `$table->foreignId('column')->constrained()` are automatically indexed.
- **Identify Missing Indexes:** Look through Filament Resources for columns chained with `->searchable()` or `->sortable()` (e.g., `name`, `status`, `type`, `email`, `phone`).
- **Create the Migration:** Create a single centralized migration to add these indexes:
  ```php
  Schema::table('products', function (Blueprint $table) {
      $table->index('name');
      $table->index('is_active');
  });
  ```

## 2. Eliminating N+1 Queries (Eager Loading)
Filament Tables frequently suffer from the N+1 query problem when displaying related data.

1. **Activate Strict Mode:** In your `AppServiceProvider.php` inside the `boot()` method, add:
   ```php
   Model::preventLazyLoading(! $this->app->isProduction());
   ```
2. **Refactor Table Columns:** Instead of displaying raw foreign key IDs (e.g., `product_category_id`), use the relationship name (e.g., `category.name`).
3. **Inject Eager Loading:** Use `modifyQueryUsing` to attach the `with()` statement to the table query:
   ```php
   return $table
       ->modifyQueryUsing(fn ($query) => $query->with(['category', 'warehouse']))
       ->columns([
           TextColumn::make('category.name')->label('Category'),
           TextColumn::make('warehouse.name')->label('Warehouse'),
       ]);
   ```

## 3. Offloading Heavy Tasks to Queues (e.g., PDF Exports)
Synchronous bulk actions (like exporting thousands of records to PDF) will block the server and cause timeouts. These must be moved to background Jobs.

1. **Create the Job:** `php artisan make:job ExportRecordsJob`
2. **Implement Background Logic & Notification:**
   ```php
   class ExportRecordsJob implements ShouldQueue
   {
       use Queueable;
       
       public function __construct(public array $recordIds, public int $userId) {}
       
       public function handle()
       {
           // 1. Generate the heavy export (e.g. PDF)
           $pdf = Pdf::loadView('pdf.export', ['records' => ...]);
           $filename = 'exports/export-' . Str::uuid() . '.pdf';
           Storage::disk('public')->put($filename, $pdf->output());
           
           // 2. Notify the user
           $user = User::find($this->userId);
           Notification::make()
               ->title('Export Ready')
               ->success()
               ->actions([
                   Action::make('download')
                       ->button()
                       ->url(Storage::url($filename), shouldOpenInNewTab: true)
               ])
               ->sendToDatabase($user);
       }
   }
   ```
3. **Update the Bulk Action:** Dispatch the job and immediately notify the user it is processing:
   ```php
   BulkAction::make('export')
       ->action(function (Collection $records) {
           ExportRecordsJob::dispatch($records->pluck('id')->toArray(), auth()->id());
           
           Notification::make()
               ->title('Export Started')
               ->body('Your export has been queued and will be ready shortly.')
               ->success()
               ->send();
       })
   ```

## 4. Production Environment Configuration (.env)
The default local environment uses file-based caching and database queues, which are severely bottlenecked by disk I/O. For high performance, switch these to memory-based stores like Redis.

Ensure the target server has Redis installed, then update the `.env`:
```env
SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```
> **Warning**: Changing the `SESSION_DRIVER` will immediately log out all active users. Ensure `predis/predis` or the `phpredis` extension is installed before applying this.
