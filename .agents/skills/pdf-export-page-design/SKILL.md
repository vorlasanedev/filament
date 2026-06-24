---
name: pdf-export-page-design
description: Zero-to-Hero instructions on how to export records to a custom designed PDF document using dompdf in Filament bulk actions.
---

# Skill: PDF Export & Page Design (Zero to Hero)

This comprehensive guide explains how to build a robust PDF export feature in a Filament Resource from scratch using `barryvdh/laravel-dompdf`. This is especially useful for generating things like Invoices, User Information Forms, or Reports that need to be strictly formatted for A4 printing.

## Phase 1: Setup & Installation

Before you can generate PDFs, you need the PDF rendering engine.

1. Install the package via composer:
   ```bash
   composer require barryvdh/laravel-dompdf
   ```
2. *(Optional)* Publish the config if you need to change default paper sizes or fonts:
   ```bash
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```

## Phase 2: Designing the PDF Template

DOMPDF is an incredible tool, but it parses HTML and CSS using an older engine. This means modern layout systems like CSS Grid and Flexbox **do not work properly**.

### The Golden Rules of DOMPDF Design:
1. **Use Tables for Columns:** Use `<table width="100%">` and `<td>` for any side-by-side layouts.
2. **Use Inline CSS or internal `<style>` tags:** External stylesheets can be tricky to link depending on server configurations. Keep styles inside the Blade view.
3. **Reset Margins:** If you want full-bleed colors or absolute control, reset the `body` margin.

### The Blade File
Create a new file at `resources/views/pdf/export.blade.php`.

Here is a bulletproof "Zero-to-Hero" multi-page boilerplate:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Export</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0; 
            padding: 0;
        }
        
        /* The Header */
        .header {
            background-color: #1a6fb0;
            color: white;
            padding: 30px;
            width: 100%;
        }
        
        /* Typography */
        .section-title {
            color: #1a6fb0;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Form Inputs */
        .input-box {
            background-color: #dfe6f2;
            border: 1px solid #aebfd6;
            height: 24px;
            line-height: 24px;
            padding: 0 8px;
            width: 100%;
        }

        /* Utility Classes for Table Layouts */
        .w-100 { width: 100%; }
        .w-50 { width: 50%; }
        .pr-10 { padding-right: 10px; }
        .pl-10 { padding-left: 10px; }
    </style>
</head>
<body>

<!-- IMPORTANT: Loop through the collection of records -->
@foreach($records as $index => $record)

    <!-- 1. HEADER SECTION -->
    <div class="header">
        <h1>Report for {{ $record->name }}</h1>
    </div>

    <!-- 2. CONTENT SECTION -->
    <div style="padding: 30px 40px;">
        <div class="section-title">Details</div>
        
        <!-- Use tables for columns! -->
        <table class="w-100" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="w-50 pr-10" valign="top">
                    <div>Email Address:</div>
                    <div class="input-box">{{ $record->email }}</div>
                </td>
                <td class="w-50 pl-10" valign="top">
                    <div>Created At:</div>
                    <div class="input-box">{{ $record->created_at->format('Y-m-d') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 3. PAGE BREAK -->
    <!-- Forces the next record onto a brand new PDF page -->
    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif

@endforeach

</body>
</html>
```

## Phase 3: Wiring it into Filament

Now we need to create the button in the Filament Resource that triggers this download. Since we are usually exporting multiple rows, a **Bulk Action** is the best choice.

Open your resource class (e.g., `App\Filament\Resources\UserResource.php`).

Scroll down to the `table()` method and add this to the `groupedBulkActions([])` array:

```php
\Filament\Actions\BulkAction::make('export_pdf_bulk')
    ->label('Export to PDF')
    ->icon('heroicon-o-document-arrow-down')
    ->color('warning')
    ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
        
        // 1. Generate the PDF response via streamDownload
        return response()->streamDownload(function () use ($records) {
            
            // 2. Load the view, pass the $records, format as A4 Portrait, and output!
            echo \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.export', ['records' => $records])
                ->setPaper('a4', 'portrait')
                ->output();
                
        }, 'export-documents.pdf'); // 3. The name of the downloaded file
    })
    ->deselectRecordsAfterCompletion(),
```

### Pro-Tips for Perfect PDFs
- **A4 Formatting:** Note the `->setPaper('a4', 'portrait')` method! This ensures your PDF doesn't look stretched or warped when printed physically.
- **Images:** If you are embedding a logo (`<img src="...">`), you often must use an absolute path or convert the image to a base64 Data URI string, as DOMPDF sometimes fails to resolve relative web URLs.
- **Debugging:** If the PDF fails to generate, test the Blade view in a standard browser first (by returning it from a test Route) to ensure there are no missing PHP variables or syntax errors in your HTML.
