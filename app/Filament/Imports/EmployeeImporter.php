<?php

namespace App\Filament\Imports;

use App\Models\Employee;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class EmployeeImporter extends Importer
{
    protected static ?string $model = Employee::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->requiredMapping()
                ->rules(['required', 'regex:/^(20|21|30)\d{8}$/']),
            ImportColumn::make('position')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('salary')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): Employee
    {
        if (($email = $this->data['email'] ?? null) && $employee = Employee::where('email', $email)->first()) {
            return $employee;
        }

        if (($phone = $this->data['phone'] ?? null) && $employee = Employee::where('phone', $phone)->first()) {
            return $employee;
        }

        return new Employee();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your employee import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    public static function getCompletedNotificationTitle(Import $import): string
    {
        if ($import->successful_rows === 0 && $import->total_rows > 0) {
            return 'Import Failed';
        }

        if ($import->getFailedRowsCount() > 0) {
            return 'Import Completed with Errors';
        }

        return 'Import Completed';
    }
}
