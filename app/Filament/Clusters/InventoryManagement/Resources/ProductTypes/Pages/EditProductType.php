<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Pages;

use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\ProductTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductType extends EditRecord
{
    protected static string $resource = ProductTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getSubNavigation(): array
    {
        if (filled($cluster = static::getCluster())) {
            return $this->generateNavigationItems($cluster::getClusteredComponents());
        }

        return [];
    }
}
