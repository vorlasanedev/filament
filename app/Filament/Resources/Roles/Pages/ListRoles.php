<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('7xl')
                ->after(function (\Spatie\Permission\Models\Role $record, array $data) {
                    RoleResource::syncPermissionsFromFormData($record, $data);
                }),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
