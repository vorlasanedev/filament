<?php

namespace App\Filament\Clusters\InventoryManagement\Resources\ProductTypes;

use App\Filament\Clusters\InventoryManagement\InventoryManagementCluster;
use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Pages\CreateProductType;
use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Pages\EditProductType;
use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Pages\ListProductTypes;
use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Schemas\ProductTypeForm;
use App\Filament\Clusters\InventoryManagement\Resources\ProductTypes\Tables\ProductTypesTable;
use App\Models\ProductType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductTypeResource extends Resource
{
    protected static ?string $model = ProductType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = InventoryManagementCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductTypesTable::configure($table);
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
            'index' => ListProductTypes::route('/'),
            'create' => CreateProductType::route('/create'),
            'edit' => EditProductType::route('/{record}/edit'),
        ];
    }

    public static function getSubNavigationPosition(): \Filament\Pages\Enums\SubNavigationPosition
    {
        return \Filament\Pages\Enums\SubNavigationPosition::Top;
    }
}
