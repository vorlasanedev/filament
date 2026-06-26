<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Pages;

use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\ProductTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductType extends CreateRecord
{
    protected static string $resource = ProductTypeResource::class;
}
