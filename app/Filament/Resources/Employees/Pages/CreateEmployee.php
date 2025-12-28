<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
    // Disable Create Another button
    protected static bool $canCreateAnother = false;
    // When Save Redirect to index
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
