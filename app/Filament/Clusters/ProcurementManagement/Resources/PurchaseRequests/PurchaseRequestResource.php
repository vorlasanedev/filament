<?php

namespace App\Filament\Clusters\ProcurementManagement\Resources\PurchaseRequests;

use App\Filament\Clusters\ProcurementManagement\ProcurementManagementCluster;
use App\Filament\Clusters\ProcurementManagement\Resources\PurchaseRequests\Pages\ManagePurchaseRequests;
use App\Models\PurchaseRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Filament\Clusters\ProcurementManagement\Resources\PurchaseRequests\RelationManagers;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseRequestResource extends Resource
{
    protected static ?string $model = PurchaseRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = ProcurementManagementCluster::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'request_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('request_number')
                    ->label(__('procurement.request_number'))
                    ->required(),
                Select::make('requester_id')
                    ->relationship('requester', 'name')
                    ->label(__('procurement.requester'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->label(__('procurement.department'))
                    ->searchable()
                    ->preload(),
                TextInput::make('fund_code')
                    ->label(__('procurement.fund_code')),
                Select::make('currency')
                    ->label(__('procurement.currency'))
                    ->options([
                        'LAK' => 'LAK - Kip',
                        'USD' => 'USD - US Dollar',
                        'THB' => 'THB - Thai Baht',
                    ])
                    ->required()
                    ->default('LAK'),
                TextInput::make('exchange_rate')
                    ->label(__('procurement.exchange_rate'))
                    ->required()
                    ->numeric()
                    ->default(1.0),
                Textarea::make('justification')
                    ->label(__('procurement.justification'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('procurement.status'))
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                        'PO Created' => 'PO Created',
                    ])
                    ->required()
                    ->default('Pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('request_number')
            ->columns([
                TextColumn::make('request_number')
                    ->label(__('procurement.request_number'))
                    ->searchable(),
                TextColumn::make('requester.name')
                    ->label(__('procurement.requester'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('department.name')
                    ->label(__('procurement.department'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fund_code')
                    ->label(__('procurement.fund_code'))
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
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'PO Created' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
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
            RelationManagers\ApprovalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePurchaseRequests::route('/'),
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
