---
name: pdf-export-page-design
description: Instructions on how to export records to a custom designed PDF document using dompdf in Filament bulk actions.
---

# Skill: PDF Export & Page Design

This skill outlines how to generate custom PDF reports and forms from selected records in a Filament Resource using the arryvdh/laravel-dompdf package.

## 1. Prerequisites
Ensure the DOMPDF package is installed in your project (which it usually is if you are using this skill):
``bash
composer require barryvdh/laravel-dompdf
``

## 2. Create the Blade View
Create a blade file for your PDF design, e.g., "resources/views/pdf/user-export.blade.php".

**Design Considerations for DOMPDF:**
DOMPDF does not support modern CSS like Flexbox or CSS Grid perfectly. When designing complex layouts (like side-by-side columns):
- Use <table> elements for robust column layouts.
- Use inline CSS or simple <style> blocks.
- Ensure you zero out default margins if necessary: ody { margin: 0; padding: 0; }.

**Example Multi-Page Layout:**
If you are iterating over multiple records (bulk export), use a @foreach loop and add a CSS page break after each iteration except the last one:

``html
<body>
@foreach($records as $record)
    <!-- Your Form HTML for $record here -->
    
    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
``

## 3. Add the Bulk Action to the Resource
Inside your resource's 	able() method (e.g., "UserResource.php"), add a custom BulkAction that streams the generated PDF to the user's browser.

``php
use Filament\Actions\BulkAction; // For standalone bulk actions

->groupedBulkActions([
    \Filament\Actions\BulkAction::make('export_pdf_bulk')
        ->label('Export Forms (PDF)')
        ->icon('heroicon-o-document-arrow-down')
        ->color('warning')
        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
            return response()->streamDownload(function () use ($records) {
                // Load the Blade view and pass the records
                echo \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.user-export', ['users' => $records])->output();
            }, 'export-forms.pdf');
        })
        ->deselectRecordsAfterCompletion(),
])
``

## Tips
- Always test complex styling by rendering the Blade view as a normal HTML page first, but keep in mind DOMPDF has limitations.
- Images used in the PDF must use absolute file paths or base64 data URIs.
