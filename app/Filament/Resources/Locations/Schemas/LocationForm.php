<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('internal'),
                TextInput::make('warehouse_id')
                    ->numeric(),
                TextInput::make('parent_id')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
