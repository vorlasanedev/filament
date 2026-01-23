<?php

namespace App\Filament\Exports;

use App\Models\Employee;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\CellAlignment;

class EmployeeExporter extends Exporter
{
    protected static ?string $model = Employee::class;

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontName('Phetsarath OT')
            ->setFontBold()
            ->setFontSize(12)
            ->setBackgroundColor('BDD7EE') // Light blue background for header
            ->setCellAlignment(CellAlignment::CENTER);
    }

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setFontName('Phetsarath OT')
            ->setFontSize(11);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('first_name')
                ->label('First name'),
            ExportColumn::make('last_name')
                ->label('Last name'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('phone')
                ->label('Phone'),
            ExportColumn::make('position')
                ->label('Position'),
            ExportColumn::make('salary')
                ->label('Salary'),
            ExportColumn::make('created_at')
                ->label('Created at'),
            ExportColumn::make('updated_at')
                ->label('Updated at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your employee export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
