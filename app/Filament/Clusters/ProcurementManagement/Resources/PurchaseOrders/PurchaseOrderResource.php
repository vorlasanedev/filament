<?php

namespace App\Filament\Clusters\ProcurementManagement\Resources\PurchaseOrders;

use App\Filament\Clusters\ProcurementManagement\ProcurementManagementCluster;
use App\Filament\Clusters\ProcurementManagement\Resources\PurchaseOrders\Pages\ManagePurchaseOrders;
use App\Models\PurchaseOrder;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Clusters\ProcurementManagement\Resources\PurchaseOrders\RelationManagers;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = ProcurementManagementCluster::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('supplier_id')
                    ->required()
                    ->numeric(),
                Select::make('purchase_request_id')
                    ->relationship('purchaseRequest', 'request_number')
                    ->label(__('procurement.purchase_request_no'))
                    ->searchable()
                    ->preload(),
                TextInput::make('currency')
                    ->required()
                    ->default('LAK'),
                TextInput::make('exchange_rate')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('status')
                    ->required()
                    ->default('Draft'),
                DateTimePicker::make('ordered_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('procurement.order_number'))
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label(__('procurement.supplier'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('purchaseRequest.request_number')
                    ->label(__('procurement.request_number'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('currency')
                    ->label(__('procurement.currency'))
                    ->searchable(),
                TextColumn::make('exchange_rate')
                    ->label(__('procurement.exchange_rate'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('procurement.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'gray',
                        'Sent' => 'warning',
                        'Received' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('ordered_at')
                    ->label(__('procurement.ordered_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

        public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePurchaseOrders::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
