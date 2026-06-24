---
name: export-users
description: Instructions on how to add Bulk Export functionality to a Filament Resource (like UserResource).
---

# Skill: Exporting Records in Filament

This skill outlines how to implement native bulk exporting in Filament v3.

## Step 1: Generate the Exporter
To export records, you first need to generate an Exporter class for your model. Run the following command:

``bash
php artisan make:filament-exporter User
``
This will create "app/Filament/Exports/UserExporter.php".

## Step 2: Configure the Exporter Columns
Open the generated Exporter class and define which columns should be exported.
``php
use Filament\Actions\Exports\ExportColumn;

public static function getColumns(): array
{
    return [
        ExportColumn::make('id')->label('ID'),
        ExportColumn::make('name'),
        ExportColumn::make('email'),
        ExportColumn::make('created_at'),
    ];
}
``

## Step 3: Add the Export Bulk Action to the Resource
Go to your Resource (e.g., "UserResource.php"), and in the 	able() method's ulkActions(), add the ExportBulkAction.

**Important Note regarding Filament Namespaces:**
Ensure you import the correct namespace for ExportBulkAction. Depending on your exact Filament build, it might be located in Filament\Actions\ExportBulkAction instead of Filament\Tables\Actions\ExportBulkAction.

``php
use Filament\Actions\ExportBulkAction;
use App\Filament\Exports\UserExporter;

// Inside the table() method:
->bulkActions([
    Tables\Actions\BulkActionGroup::make([
        // ... other actions like DeleteBulkAction
        ExportBulkAction::make()
            ->exporter(UserExporter::class),
    ]),
])
``

## Step 4: Ensure Queues are Configured
Native exports use jobs in the background! If you do not have a queue worker running, the export will not finish and the user will never get the "Download" link.

Refer to the "how-to-run-queue-work" skill for details. If testing locally, either run php artisan queue:work or set QUEUE_CONNECTION=sync in your .env.
