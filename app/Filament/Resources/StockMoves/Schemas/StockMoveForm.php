<?php

namespace App\Filament\Resources\StockMoves\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMoveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('stock_transfer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('description'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                TextInput::make('source_location_id')
                    ->numeric(),
                TextInput::make('destination_location_id')
                    ->numeric(),
            ]);
    }
}
