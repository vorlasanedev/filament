<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Imports\EmployeeImporter;
use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    protected static ?string $title = 'All Employees';
    protected ?string $heading = 'Employee List';

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(EmployeeImporter::class)
                ->icon('heroicon-o-arrow-up-tray'),
            Action::make('export_excel')
                ->label('Export All Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new EmployeesExport, 'employees.xlsx');
                }),
            CreateAction::make()
                ->createAnother(false),
        ];
    }
    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
