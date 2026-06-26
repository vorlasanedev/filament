<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Pages;

use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\ProductUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductUnit extends CreateRecord
{
    protected static string $resource = ProductUnitResource::class;
}
