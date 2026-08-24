<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Select;
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
                Select::make('location_type_id')
                    ->relationship('locationType', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required(),
                    ])
                    ->required(),
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->preload()
                    ->live(),
                Select::make('parent_id')
                    ->relationship('parent', 'name', fn (\Illuminate\Database\Eloquent\Builder $query, $get) => $query->where('warehouse_id', $get('warehouse_id')))
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
