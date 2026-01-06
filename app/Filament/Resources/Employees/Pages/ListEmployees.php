<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Imports\EmployeeImporter;
use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    protected static ?string $title = 'All Employees';
    protected ?string $heading = 'Employee List';

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(EmployeeImporter::class),
            CreateAction::make()
                ->createAnother(false),
        ];
    }
}
