<?php

namespace App\Filament\Resources\StockTransfers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockTransfersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['sourceLocation', 'destinationLocation']))
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('sourceLocation.name')
                    ->label('Source Location')
                    ->sortable(),
                TextColumn::make('destinationLocation.name')
                    ->label('Destination Location')
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('scheduled_date')
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
