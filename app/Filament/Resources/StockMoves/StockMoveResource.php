<?php

namespace App\Filament\Resources\StockMoves;

use App\Filament\Resources\StockMoves\Pages\CreateStockMove;
use App\Filament\Resources\StockMoves\Pages\EditStockMove;
use App\Filament\Resources\StockMoves\Pages\ListStockMoves;
use App\Filament\Resources\StockMoves\Schemas\StockMoveForm;
use App\Filament\Resources\StockMoves\Tables\StockMovesTable;
use App\Models\StockMove;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockMoveResource extends Resource
{
    protected static ?string $model = StockMove::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static ?string $cluster = \App\Filament\Clusters\InventoryManagement\InventoryManagementCluster::class;

    protected static string|UnitEnum|null $navigationGroup = 'Report';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return StockMoveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMovesTable::configure($table);
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
            'index' => ListStockMoves::route('/'),
            'create' => CreateStockMove::route('/create'),
            'edit' => EditStockMove::route('/{record}/edit'),
        ];
    }
}
