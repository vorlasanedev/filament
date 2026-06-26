<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
