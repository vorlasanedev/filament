<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Pages;

use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\ProductTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductTypes extends ListRecords
{
    protected static string $resource = ProductTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
