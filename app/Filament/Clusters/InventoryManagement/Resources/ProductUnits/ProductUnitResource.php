<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductUnits;

use App\Filament\Clusters\InventoryManagement\InventoryManagementCluster;
use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Pages\CreateProductUnit;
use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Pages\EditProductUnit;
use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Pages\ListProductUnits;
use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Schemas\ProductUnitForm;
use App\Filament\Clusters\InventoryManagement\Resources\ProductUnits\Tables\ProductUnitsTable;
use App\Models\ProductUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductUnitResource extends Resource
{
    protected static ?string $model = ProductUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = InventoryManagementCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuration';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductUnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductUnits::route('/'),
            'create' => CreateProductUnit::route('/create'),
            'edit' => EditProductUnit::route('/{record}/edit'),
        ];
    }
}
