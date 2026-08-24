<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Pages;

use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\ProductUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductUnits extends ListRecords
{
    protected static string $resource = ProductUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
