<?php

namespace App\Filament\Resources\StockTransfers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reference')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('source_location_id')
                    ->numeric(),
                TextInput::make('destination_location_id')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DateTimePicker::make('scheduled_date'),
            ]);
    }
}
