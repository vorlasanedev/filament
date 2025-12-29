<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    protected static ?string $title = 'All Employees';
    protected ?string $heading = 'Employee List';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->createAnother(false),
        ];
    }
}
