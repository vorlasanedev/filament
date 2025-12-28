<?php

namespace App\Filament\Resources\Employees\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Employees\EmployeeResource;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
            ->label('Back')
            ->url(EmployeeResource::getUrl('index'))
            ->color('success')
            ->icon('heroicon-o-arrow-left')
        ];
    }
}
