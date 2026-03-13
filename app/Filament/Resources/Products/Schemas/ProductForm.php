<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU'),
                TextInput::make('type')
                    ->required()
                    ->default('storable'),
                TextInput::make('unit')
                    ->required()
                    ->default('pcs'),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('weight')
                    ->numeric(),
                TextInput::make('strategy')
                    ->required()
                    ->default('MTS'),
                TextInput::make('safety_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('lead_time')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('product_category_id')
                    ->numeric(),
            ]);
    }
}
