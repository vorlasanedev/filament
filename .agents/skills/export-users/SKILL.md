---
name: export-users
description: Zero-to-Hero instructions on how to add Bulk Export functionality to a Filament Resource.
---

# Skill: Exporting Records in Filament (Zero to Hero)

This comprehensive guide details how to implement native, background-processed bulk exporting in Filament v3 from scratch.

## Phase 1: Generate the Exporter

Filament handles exports using an `Exporter` class.

1. Generate the Exporter using Artisan:
   ```bash
   php artisan make:filament-exporter User
   ```
2. This creates `app/Filament/Exports/UserExporter.php`. Open this file to define what columns get exported.

## Phase 2: Configure the Exporter Columns

In the `UserExporter.php` file, map the database fields to CSV columns.

```php
namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('created_at')->label('Joined At')->date(),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }
        return $body;
    }
}
```

## Phase 3: Wiring it into Filament

Now, add the Bulk Action to your Resource table (e.g., `UserResource.php`).

```php
use Filament\Actions\ExportBulkAction; // Import the Action!
use App\Filament\Exports\UserExporter;

public static function table(Table $table): Table
{
    return $table
        // ... columns and filters ...
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                
                // Add the Export action here
                ExportBulkAction::make()
                    ->exporter(UserExporter::class),
            ]),
        ]);
}
```

## Phase 4: The Critical Step (Queues)

**IMPORTANT:** Filament exports run as background jobs by default to prevent timeouts on large tables. If a worker is not running, the export will *start* but the user will never receive the download notification.

Ensure your `.env` is configured properly.

**For Local Development (No Worker Needed):**
```env
QUEUE_CONNECTION=sync
```
*(With `sync`, the export runs immediately in the browser request. Only use this for small tables or local testing).*

**For Production (Worker Required):**
```env
QUEUE_CONNECTION=database
```
*(Run `php artisan queue:work` to process the jobs in the background).*
